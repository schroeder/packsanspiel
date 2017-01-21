<?php

namespace PacksAnSpielBundle\Game;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use PacksAnSpielBundle\Entity\Actionlog;
use Symfony\Component\HttpKernel\Tests\Fixtures\Controller\NullableController;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Entity\Game;

class GameActionLogger
{
    /* @var EntityManager $em */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    function logAction($message, $logLevel = Actionlog::LOGLEVEL_INFO, Team $team = NULL, Game $game = NULL, $log_time = 0)
    {
        if ($log_time === 0) {
            $log_time = time();
        }

        try {
            $log = new Actionlog();
            $log->setLogLevel($logLevel);
            $log->setLogText($message);
            $log->setTeam($team);
            $log->setGame($game);
            $log->setTimestamp($log_time);

            $this->em->persist($log);
            $this->em->flush();

        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }
}