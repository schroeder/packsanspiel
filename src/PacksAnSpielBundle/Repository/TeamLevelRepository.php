<?php

namespace PacksAnSpielBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Entity\Member;
use PacksAnSpielBundle\Repository\MemberRepository;

class TeamLevelRepository extends EntityRepository
{
    public function findCurrentTeamLevel($team, $level)
    {
        return $this->findOneBy(['team' => $team, 'level' => $level]);
    }
}
