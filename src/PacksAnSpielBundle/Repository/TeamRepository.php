<?php

namespace PacksAnSpielBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


class TeamRepository extends EntityRepository implements UserProviderInterface
{
    public function findOneByPasscode($passcode)
    {
        $result = $this->_em->createQuery('SELECT id FROM PacksAnSpielBundle\Entity\Team t WHERE t.passcode= "$passcode"')
            ->getFirstResult();
        var_dump($result);
        return $result;
    }

}
