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

    public function findAFreeGame(TeamLevel $teamLevel)
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
                                AND team_level.team_id=" . $team->getId() . ") 
                            AND g.id NOT IN (
                                SELECT team_level_game.assigned_game 
                                FROM team_level_game 
                                WHERE team_level_game.start_time IS NOT NULL 
                                AND team_level_game.finish_time IS NULL) 
                            LIMIT 1";
        $query = $this->_em->createNativeQuery($queryString, $rsm);
        $game = $query->execute();

        if (!$game) {

        }

        return $game;
    }

    public function getCurrentGame(Team $team)
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

    public function countActiveGames($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(g.assignedGame) AS activeGames FROM PacksAnSpielBundle\Entity\TeamLevelGame g WHERE g.assignedGame=' . $id . ' AND g.startTime IS NOT NULL AND g.finishTime IS NULL')
            ->getSingleScalarResult();
    }

    public function countFinishedGames($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(g.assignedGame) AS activeGames FROM PacksAnSpielBundle\Entity\TeamLevelGame g WHERE g.assignedGame=' . $id . ' AND g.startTime IS NOT NULL AND g.finishTime IS NOT NULL')
            ->getSingleScalarResult();
    }
}
