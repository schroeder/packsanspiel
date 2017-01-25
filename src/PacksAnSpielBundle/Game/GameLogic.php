<?php

namespace PacksAnSpielBundle\Game;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Repository\GameSubjectRepository;
use PacksAnSpielBundle\Entity\TeamLevelGame;
use PacksAnSpielBundle\Entity\TeamLevel;

class GameLogic
{
    /* @var EntityManager $em */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    function initializeFirstLevel(Team $team)
    {
        /* @var GameSubjectRepository $repository */
        $repository = $this->em->getRepository("PacksAnSpielBundle:GameSubject");
        // TODO: Replace by better function
        $gameSubjectList = $repository->getFourRandomGameSubjects();

        $level = $this->getLevel(1);

        if ($level == false) {
            // TODO handle error
        }

        $teamLevel = new TeamLevel();
        $teamLevel->setTeam($team);
        $teamLevel->setStartTime(GameLogic::now());
        $teamLevel->setLevel($level);

        $this->em->persist($teamLevel);
        $this->em->flush();

        foreach ($gameSubjectList as $gameSubject) {
            $teamLevelGame = new TeamLevelGame();
            $teamLevelGame->setAssignedGameSubject($gameSubject);
            $teamLevelGame->setStartTime(GameLogic::now());
            $teamLevelGame->setTeamLevel($teamLevel);
            $this->em->persist($teamLevelGame);
        }
        $team->setCurrentLevel($level);
        $this->em->persist($team);
        $this->em->flush();

        return true;
    }

    public function mergeAndUpgradeTeamLevel(Team $team1, Team $team2)
    {
        // TODO
    }

    public function getLevel($number = 1)
    {
        /* @var GameSubjectRepository $repository */
        $repository = $this->em->getRepository("PacksAnSpielBundle:Level");

        $result = $repository->findBy(['number' => $number]);
        if (count($result) >= 1) {
            return $result[0];
        }
        return false;
    }

    static public function now()
    {
        return time();
    }
}