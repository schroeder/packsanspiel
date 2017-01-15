<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Member
 *
 * @ORM\Table(name="member", indexes={@ORM\Index(name="fk_member_team1_idx", columns={"team_id"})})
 * @ORM\Entity
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
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="stufe", type="string", length=45, nullable=true)
     */
    private $stufe;

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
     * Set stufe
     *
     * @param string $stufe
     *
     * @return Member
     */
    public function setStufe($stufe)
    {
        $this->stufe = $stufe;

        return $this;
    }

    /**
     * Get stufe
     *
     * @return string
     */
    public function getStufe()
    {
        return $this->stufe;
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
