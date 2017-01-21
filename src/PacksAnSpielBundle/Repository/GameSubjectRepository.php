<?php

namespace PacksAnSpielBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use PacksAnSpielBundle\Entity\GameSubject;


class GameSubjectRepository extends EntityRepository
{
    public function getFourRandomGameSubjects($limit = 4)
    {
        $em = $this->getEntityManager();
        $elements = [];
        $rows = $em->createQuery('SELECT COUNT(u.id) FROM PacksAnSpielBundle\Entity\GameSubject u')->getSingleScalarResult();

        while (count($elements) != 4) {
            $offset = max(0, rand(0, $rows - $limit - 1));
            $query = $em->createQuery('SELECT DISTINCT u FROM PacksAnSpielBundle\Entity\GameSubject u')
                ->setMaxResults(1)
                ->setFirstResult($offset);

            /* @var PacksAnSpielBundle\Entity\GameSubject $result */
            $result = $query->getResult();

            if (count($result)) {
                $elements[$result[0]->getId()] = $result[0];
            }
        }

        return $elements;
    }
}
