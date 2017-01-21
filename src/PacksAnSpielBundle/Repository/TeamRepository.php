<?php

namespace PacksAnSpielBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use PacksAnSpielBundle\Entity\Team;

class TeamRepository extends EntityRepository implements UserProviderInterface
{
    public function findOneByPasscode($passcode)
    {
        //$result = $this->_em->createQuery('SELECT t.id FROM PacksAnSpielBundle\Entity\Team t WHERE t.passcode= :passcode')->setParameter('passcode', $passcode)->getFirstResult();
        //return $result;
        $result = $this->_em->createQuery('SELECT t.id FROM PacksAnSpielBundle\Entity\Team t WHERE t.passcode= :passcode')->setParameter('passcode', $passcode)->execute();
        if (count($result) == 1) {
            return $this->find($result[0]['id']);
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
}
