<?php

namespace PacksAnSpielBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use PacksAnSpielBundle\Entity\GameSubject;
use PacksAnSpielBundle\Entity\Message;
use PacksAnSpielBundle\Game\GameLogic;

class MessageRepository extends EntityRepository
{

    public function findOneByTeam($teamId)
    {
        $result = $this->_em->createQuery("SELECT m.id FROM PacksAnSpielBundle\Entity\Message m WHERE m.team=:team_id AND m.readTime IS NULL")
            ->setParameters(array('team_id' => $teamId))
            ->execute();
        if (count($result) == 1) {
            return $this->find($result[0]['id']);
        }
        return false;
    }

    public function findOneByGame($gameId)
    {
        $result = $this->_em->createQuery("SELECT m.id FROM PacksAnSpielBundle\Entity\Message m WHERE m.game=:game_id AND m.readTime IS NULL")
            ->setParameters(array('game_id' => $gameId))
            ->execute();
        if (count($result) == 1) {
            return $this->find($result[0]['id']);
        }
        return false;
    }

    public function setMessageAsRead($messageId)
    {
        $em = $this->getEntityManager();

        /* @var Message $message */
        $message = $this->find($messageId);
        $message->setReadTime(GameLogic::now());
        $em->persist($message);
        $em->flush();

        return true;
    }
}
