<?php

namespace PacksAnSpielBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Entity\Member;
use PacksAnSpielBundle\Repository\MemberRepository;

class TeamRepository extends EntityRepository implements UserProviderInterface
{
    public function findOneByPasscode($passcode)
    {
        //$result = $this->_em->createQuery('SELECT t.id FROM PacksAnSpielBundle\Entity\Team t WHERE t.passcode= :passcode')->setParameter('passcode', $passcode)->getFirstResult();
        //return $result;
        $result = $this->_em->createQuery('SELECT t.id FROM PacksAnSpielBundle\Entity\Team t WHERE t.passcode= :passcode AND t.status > 1')->setParameter('passcode', $passcode)->execute();
        if (count($result) == 1) {
            return $this->find($result[0]['id']);
        }
        return false;
    }

    public function findOneUnregisteredByPasscode($passcode)
    {
        //$result = $this->_em->createQuery('SELECT t.id FROM PacksAnSpielBundle\Entity\Team t WHERE t.passcode= :passcode')->setParameter('passcode', $passcode)->getFirstResult();
        //return $result;
        $result = $this->_em->createQuery('SELECT t.id FROM PacksAnSpielBundle\Entity\Team t WHERE t.passcode= :passcode AND t.status <= 1')->setParameter('passcode', $passcode)->execute();
        if (count($result) == 1) {
            return $this->find($result[0]['id']);
        }
        return false;
    }

    public function findLeadingGroup($passcode)
    {
        $result = $this->_em->createQuery('SELECT t2.id FROM 
                PacksAnSpielBundle\Entity\Team t1, 
                PacksAnSpielBundle\Entity\Team t2 
                WHERE t1.passcode= :passcode 
                AND t2.passcode=t1.parentTeam
                AND t1.status > 1
                AND t2.status > 1')->setParameter('passcode', $passcode)->execute();
        if (count($result) == 1) {
            return $this->find($result[0]['id']);
        }
        return false;
    }

    public function teamAlreadyUsedJoker($teamId)
    {
        $result = $this->_em->createQuery('SELECT tlg.id FROM PacksAnSpielBundle\Entity\TeamLevelGame tlg, 
            PacksAnSpielBundle\Entity\TeamLevel tl 
            WHERE tlg.teamLevel=tl.id AND tl.team=:teamId AND tlg.usedJoker IS NOT NULL')->setParameter('teamId', $teamId)->execute();
        if (count($result) == 1) {
            return true;
        }
        return false;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        return $this->findOneByPasscode($username);
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return true;
    }

    public function initializeNewTeam($teamCode, $status = Team::STATUS_IN_REGISTRATION)
    {
        if (!$eam = $this->findOneByPasscode($teamCode)) {
            $team = new Team();
            $team->setPasscode($teamCode);
            //$team->setParentTeam($teamCode);
            $team->setStatus($status);
            $team->setCurrentLevel(NULL);

            $this->_em->persist($team);
            $this->_em->flush();
        }

        return $team;
    }

    public function setTeamMember($team, $memberList)
    {
        /* @var Member $member */
        foreach ($memberList as $member) {
            $member->setTeam($team);
            $this->_em->persist($member);
            $this->_em->flush();
        }
        $team->setStatus(Team::STATUS_ACTIVE);
        $this->_em->persist($team);
        $this->_em->flush();

        return $team;
    }

    public function getCurrentTeamLevelGame($team)
    {
        $result = $this->_em->createQuery('SELECT t2.id FROM 
                PacksAnSpielBundle\Entity\Team team, 
                PacksAnSpielBundle\Entity\Team t2 
                WHERE t1.passcode= :passcode 
                AND t2.passcode=t1.parentTeam
                AND t1.status > 1
                AND t2.status > 1')->setParameter('passcode', $passcode)->execute();

        $team->setStatus(Team::STATUS_ACTIVE);
        $this->_em->persist($team);
        $this->_em->flush();

        return $team;
    }

}
