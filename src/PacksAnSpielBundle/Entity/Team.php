<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PacksAnSpielBundle\Entity\Member;
use PacksAnSpielBundle\Entity\Level;
use PacksAnSpielBundle\Repository\TeamLevelRepository;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Team
 *
 * @ORM\Table(name="team", indexes={@ORM\Index(name="fk_team_level1_idx", columns={"current_level"})})
 * @ORM\Entity(repositoryClass="PacksAnSpielBundle\Repository\TeamRepository")
 */
class Team implements UserInterface, \Serializable
{
    const STATUS_UNUSED = 0;
    const STATUS_IN_REGISTRATION = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_BLOCKED = 3;
    const STATUS_ADMIN = 4;
    const STATUS_GAME = 5;

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
     * Dummy field for authorization, not really used!
     */
    private $username;

    /**
     * @var string
     * Dummy field for authorization, not really used!
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="parent_team", type="string", length=45, nullable=true)
     */
    private $parentTeam;

    private $childTeams;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=15, nullable=true)
     */
    private $grade;

    /**
     * @var PacksAnSpielBundle\Entity\Level
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\Level")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="current_level", referencedColumnName="id")
     * })
     */
    private $currentLevel;

    /**
     * @var Collection
     *
     * @OneToMany(targetEntity="Member", mappedBy="team", fetch="EAGER")
     */
    private $teamMembers;

    /**
     * @var Collection
     *
     * @OneToMany(targetEntity="Actionlog", mappedBy="team", cascade={"persist", "remove", "merge"}, orphanRemoval=true, mappedBy="logEntries")
     */
    private $logEntries;

    public function __construct()
    {
        $this->teamMembers = new ArrayCollection();
        $this->childTeams = new ArrayCollection();
    }

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
     * @param Team $memberOfTeam
     *
     * @return PacksAnSpielBundle\Entity\Team
     */
    public function setParentTeam($parentTeam)
    {
        $this->parentTeam = $parentTeam;

        return $this;
    }

    /**
     * Set grade
     *
     * @param string $grade
     *
     * @return Game
     */
    public function setGrade($grade = null)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Get memberOfTeam
     *
     * @return PacksAnSpielBundle\Entity\Team
     */
    public function getParentTeam()
    {
        return $this->parentTeam;
    }

    /**
     * Get memberOfTeam
     *
     * @return ArrayCollection<PacksAnSpielBundle\Entity\Team>
     */
    public function getChildTeams()
    {
        return $this->childTeams;
    }

    /**
     * Get memberOfTeam
     *
     * @return ArrayCollection<PacksAnSpielBundle\Entity\Member>
     */
    public function getTeamMembers()
    {
        return $this->teamMembers;
    }

    /**
     * Get memberOfTeam
     *
     * @return ArrayCollection<PacksAnSpielBundle\Entity\Actionlog>
     */
    public function getLogEntries()
    {
        return $this->logEntries;
    }


    /**
     * Get countPersons
     *
     * @return integer
     */
    public function getCountMembers()
    {
        return $this->getTeamMembers() ? $this->getTeamMembers()->count() : 0;
    }

    /**
     * Get countPersons
     *
     * @return integer
     */
    public function getCountGroups()
    {
        return $this->getChildTeams()->count();
    }

    /**
     * Set currentLevel
     *
     * @param \PacksAnSpielBundle\Entity\Level $currentLevel
     *
     * @return Team
     */
    public function setCurrentLevel(Level $currentLevel = null)
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
        if ($this->getStatus() == Team::STATUS_ADMIN) {
            return array('ROLE_ADMIN');
        } elseif ($this->getStatus() == Team::STATUS_GAME) {
            return array('ROLE_GAME');
        }
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
        return $this->passcode;
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
