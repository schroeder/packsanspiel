<?php

namespace PacksAnSpielBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use PacksAnSpielBundle\Entity\Member;

class MemberRepository extends EntityRepository
{
    public function findOneByPasscode($passcode)
    {
        $result = $this->_em->createQuery("SELECT t.id FROM PacksAnSpielBundle\Entity\Member t WHERE t.passcode= :passcode AND t.grade!= :stufe")
            ->setParameters(array('passcode' => $passcode, 'stufe' => "admin"))
            ->execute();
        if (count($result) == 1) {
            return $this->find($result[0]['id']);
        }
        return false;
    }

    public function findAdminByPasscode($passcode)
    {
        $result = $this->_em->createQuery("SELECT t.id FROM PacksAnSpielBundle\Entity\Member t WHERE t.passcode= :passcode AND t.grade= :stufe")
            ->setParameters(array('passcode' => $passcode, 'stufe' => "admin"))
            ->execute();
        if (count($result) == 1) {
            return $this->find($result[0]['id']);
        }
        return false;
    }
}
