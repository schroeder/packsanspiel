<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Member
 *
 * @ORM\Table(name="member", indexes={@ORM\Index(name="fk_member_team1_idx", columns={"team_id"})})
 * @ORM\Entity(repositoryClass="PacksAnSpielBundle\Repository\MemberRepository")
 */
class Member
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
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=45, nullable=true)
     */
    private $grade;

    /**
     * @var \PacksAnSpielBundle\Entity\Team
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;


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
     * @return Member
     */
    public function setPasscode($passcode)
    {
        $this->passcode = $passcode;
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
     * Set name
     *
     * @param string $name
     *
     * @return Member
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set grade
     *
     * @param string $grade
     *
     * @return Member
     */
    public function setGrade($stufe)
    {
        $this->grade = $stufe;

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
     * Set team
     *
     * @param \PacksAnSpielBundle\Entity\Team $team
     *
     * @return Member
     */
    public function setTeam(\PacksAnSpielBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \PacksAnSpielBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }
}
