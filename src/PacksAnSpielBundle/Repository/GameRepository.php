<?php

namespace PacksAnSpielBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use PacksAnSpielBundle\Entity\Game;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Entity\TeamLevel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\ResultSetMapping;

class GameRepository extends EntityRepository
{
    public function findGameByPasscode($passcode)
    {
        $result = $this->_em->createQuery("SELECT t.id FROM PacksAnSpielBundle\Entity\Game t WHERE t.passcode= :passcode")
            ->setParameters(array('passcode' => $passcode))
            ->execute();
        if (count($result) == 1) {
            return $this->find($result[0]['id']);
        }
        return false;
    }

    public function findAFreeGame(TeamLevel $teamLevel, $excludeIdentifierList = [])
    {
        $level = $teamLevel->getLevel();
        $team = $teamLevel->getTeam();
        $grade = Game::getCorrectLevelGrade($team->getGrade(), $level->getNumber());

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('PacksAnSpielBundle:Game', 'g');
        $rsm->addFieldResult('g', 'id', 'id');

        $queryString = "SELECT g.id FROM game g 
                            WHERE g.status>0 
                            AND g.grade='" . $grade . "' 
                            AND g.level_id=" . $level->getId() . " 
                            AND g.id NOT IN (
                                SELECT team_level_game.assigned_game 
                                FROM team_level_game, team_level 
                                WHERE team_level.id=team_level_game.team_level_id
                                AND assigned_game IS NOT NULL
                                AND team_level.team_id=" . $team->getId() . ") 
                            AND g.id NOT IN (
                                SELECT team_level_game.assigned_game 
                                FROM team_level_game 
                                WHERE team_level_game.start_time IS NOT NULL 
                                AND assigned_game IS NOT NULL
                                AND team_level_game.finish_time IS NULL)";
        foreach ($excludeIdentifierList as $identifier) {
            $queryString .= " AND g.id NOT IN (
                SELECT gex.id FROM game gex 
                                WHERE gex.identifier IS LIKE \"$identifier%\") ";

        }


        $queryString .= " ORDER BY priority DESC LIMIT 1";
        $query = $this->_em->createNativeQuery($queryString, $rsm);
        $game = $query->execute();
        if ($game && is_array($game) && count($game) == 1) {
            return $game[0];
        } else {
            $queryString = "SELECT g.id FROM game g 
                            WHERE g.status>0 
                            AND g.grade='" . $grade . "' 
                            AND g.level_id=" . $level->getId() . " ";
            foreach ($excludeIdentifierList as $identifier) {
                $queryString .= " AND g.id NOT IN (
                SELECT gex.id FROM game gex 
                                WHERE gex.identifier IS LIKE \"$identifier%\") ";

            }
            $queryString .= " ORDER BY priority DESC LIMIT 1";
            $query = $this->_em->createNativeQuery($queryString, $rsm);
            $game = $query->execute();
            if ($game && is_array($game) && count($game) == 1) {
                return $game[0];
            } else {
                $queryString = "SELECT g.id FROM game g 
                            WHERE g.status>0 
                            AND g.grade='" . $grade . "' 
                            AND g.level_id=" . $level->getId() . " ";
                $queryString .= " ORDER BY priority DESC LIMIT 1";
                $query = $this->_em->createNativeQuery($queryString, $rsm);
                $game = $query->execute();
                if ($game && is_array($game) && count($game) == 1) {
                    return $game[0];
                }
            }
        }
        return false;
    }

    public
    function getCurrentGame(Team $team)
    {
        $result = $this->_em->createQuery('SELECT tlg.id 
                FROM PacksAnSpielBundle\Entity\TeamLevelGame tlg,
                PacksAnSpielBundle\Entity\TeamLevel tl 
                WHERE tl.id=tlg.teamLevel 
                AND tl.team=:team_id
                AND tlg.startTime IS NOT NULL
                AND tlg.finishTime IS NULL')->setParameter('team_id', $team->getId())->execute();

        if (count($result) == 1) {
            return $this->find($result[0]['id']);
        }
        return false;
    }

    public
    function getCurrentTeams($gameId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('PacksAnSpielBundle:Game', 'g');
        $rsm->addEntityResult('PacksAnSpielBundle:TeamLevelGame', 'tlg');
        $rsm->addEntityResult('PacksAnSpielBundle:TeamLevel', 'tl');
        $rsm->addEntityResult('PacksAnSpielBundle:Team', 't');
        $rsm->addFieldResult('t', 'id', 'id');
        $rsm->addFieldResult('t', 'passcode', 'passcode');
        $rsm->addFieldResult('t', 'grade', 'grade');

        $queryString = "SELECT t.id, t.passcode, t.grade FROM game g 
                        JOIN team_level_game tlg 
                            ON tlg.assigned_game = g.id 
                        JOIN team_level tl 
                            ON tlg.team_level_id=tl.id 
                        JOIN team t 
                            ON tl.team_id=t.id 
                        WHERE tlg.finish_time IS NULL AND g.id=" . $gameId;

        $query = $this->_em->createNativeQuery($queryString, $rsm);
        $result = $query->execute();
        $teamList = [];
        if ($result) {
            $teamList = $result;
        }

        return $teamList;
    }

    public function getAllUnassignedGames()
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('PacksAnSpielBundle:Game', 'g');
        $rsm->addFieldResult('g', 'id', 'id');
        $rsm->addFieldResult('g', 'grade', 'grade');
        $rsm->addFieldResult('g', 'identifier', 'identifier');
        $rsm->addFieldResult('g', 'name', 'name');

        $queryString = "SELECT g.id, g.grade,g.identifier,g.name,g.location_id,g.level_id 
                    FROM game g
                    WHERE id NOT in (
                        SELECT tlg.assigned_game 
                        FROM team_level_game tlg 
                        WHERE tlg.assigned_game IS NOT NULL) 
                    ORDER BY g.level_id, g.location_id";

        $query = $this->_em->createNativeQuery($queryString, $rsm);
        $gameIdList = $query->getArrayResult();
        $gameList = [];
        foreach ($gameIdList as $game) {
            $gameList[] = $this->find($game['id']);
        }
        return $gameList;

    }


    public
    function countActiveGames($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(g.assignedGame) AS activeGames FROM PacksAnSpielBundle\Entity\TeamLevelGame g WHERE g.assignedGame=' . $id . ' AND g.startTime IS NOT NULL AND g.finishTime IS NULL')
            ->getSingleScalarResult();
    }

    public
    function countFinishedGames($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(g.assignedGame) AS activeGames FROM PacksAnSpielBundle\Entity\TeamLevelGame g WHERE g.assignedGame=' . $id . ' AND g.startTime IS NOT NULL AND g.finishTime IS NOT NULL')
            ->getSingleScalarResult();
    }

    public
    function checkPlayedRoundsOfGame($gameId)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT count(tlg.assignedGame) FROM PacksAnSpielBundle\Entity\TeamLevelGame tlg, PacksAnSpielBundle\Entity\Game g WHERE tlg.assignedGame=g.id AND g.id=' . $gameId)
            ->getSingleScalarResult();
    }


}
