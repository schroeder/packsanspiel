<?php

namespace PacksAnSpielBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use PacksAnSpielBundle\Entity\Game;

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
}
