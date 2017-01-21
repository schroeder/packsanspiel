<?php

namespace PacksAnSpielBundle\Game;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use PacksAnSpielBundle\Entity\Team;

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
        // TODO
    }
}