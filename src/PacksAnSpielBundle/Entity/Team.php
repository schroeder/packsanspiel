<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Team
 *
 * @ORM\Table(name="team", indexes={@ORM\Index(name="fk_team_level1_idx", columns={"current_level"})})
 * @ORM\Entity(repositoryClass="PacksAnSpielBundle\Repository\TeamRepository")
 */
class Team implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="passcode", type="string", length=45, nullable=true)
     */
    private $passcode;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="member_of_team", type="string", length=45, nullable=true)
     */
    private $memberOfTeam;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="count_persons", type="string", length=45, nullable=true)
     */
    private $countPersons;

    /**
     * @var \PacksAnSpielBundle\Entity\Level
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\Level")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="current_level", referencedColumnName="id")
     * })
     */
    private $currentLevel;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set passcode
     *
     * @param string $passcode
     *
     * @return Team
     */
    public function setPasscode($passcode)
    {
        $this->passcode = $passcode;
        $this->username = $passcode;
        $this->password = $passcode;
        return $this;
    }

    /**
     * Get passcode
     *
     * @return string
     */
    public function getPasscode()
    {
        return $this->passcode;
    }

    /**
     * Set memberOfTeam
     *
     * @param string $memberOfTeam
     *
     * @return Team
     */
    public function setMemberOfTeam($memberOfTeam)
    {
        $this->memberOfTeam = $memberOfTeam;

        return $this;
    }

    /**
     * Get memberOfTeam
     *
     * @return string
     */
    public function getMemberOfTeam()
    {
        return $this->memberOfTeam;
    }

    /**
     * Set countPersons
     *
     * @param string $countPersons
     *
     * @return Team
     */
    public function setCountPersons($countPersons)
    {
        $this->countPersons = $countPersons;

        return $this;
    }

    /**
     * Get countPersons
     *
     * @return string
     */
    public function getCountPersons()
    {
        return $this->countPersons;
    }

    /**
     * Set currentLevel
     *
     * @param \PacksAnSpielBundle\Entity\Level $currentLevel
     *
     * @return Team
     */
    public function setCurrentLevel(\PacksAnSpielBundle\Entity\Level $currentLevel = null)
    {
        $this->currentLevel = $currentLevel;

        return $this;
    }

    /**
     * Get currentLevel
     *
     * @return \PacksAnSpielBundle\Entity\Level
     */
    public function getCurrentLevel()
    {
        return $this->currentLevel;
    }


    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->passcode;
    }

    public function getPassword()
    {
        return $this->passcode;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->passcode,
        ));
    }

    public function __toString()
    {
        return $this->username;
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->passcode,
            ) = unserialize($serialized);
    }

    /**
     * Set status
     *
     * @param int $status
     *
     * @return Team
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
}
