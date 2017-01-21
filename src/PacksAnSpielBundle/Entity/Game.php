<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game", indexes={@ORM\Index(name="fk_game_game_subject1_idx", columns={"game_subject_id"}), @ORM\Index(name="fk_game_level1_idx", columns={"level_id"}), @ORM\Index(name="fk_game_location1_idx", columns={"location_id"})})
 * @ORM\Entity
 */
class Game
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
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="gamecol", type="string", length=45, nullable=true)
     */
    private $gamecol;

    /**
     * @var \PacksAnSpielBundle\Entity\GameSubject
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\GameSubject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="game_subject_id", referencedColumnName="id")
     * })
     */
    private $gameSubject;

    /**
     * @var \PacksAnSpielBundle\Entity\Level
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\Level")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level_id", referencedColumnName="id")
     * })
     */
    private $level;

    /**
     * @var \PacksAnSpielBundle\Entity\Location
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\Location")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * })
     */
    private $location;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;


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
     * @return Game
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
     * Set description
     *
     * @param string $description
     *
     * @return Game
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Game
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set gamecol
     *
     * @param string $gamecol
     *
     * @return Game
     */
    public function setGamecol($gamecol)
    {
        $this->gamecol = $gamecol;

        return $this;
    }

    /**
     * Get gamecol
     *
     * @return string
     */
    public function getGamecol()
    {
        return $this->gamecol;
    }

    /**
     * Set gameSubject
     *
     * @param \PacksAnSpielBundle\Entity\GameSubject $gameSubject
     *
     * @return Game
     */
    public function setGameSubject(\PacksAnSpielBundle\Entity\GameSubject $gameSubject = null)
    {
        $this->gameSubject = $gameSubject;

        return $this;
    }

    /**
     * Get gameSubject
     *
     * @return \PacksAnSpielBundle\Entity\GameSubject
     */
    public function getGameSubject()
    {
        return $this->gameSubject;
    }

    /**
     * Set level
     *
     * @param \PacksAnSpielBundle\Entity\Level $level
     *
     * @return Game
     */
    public function setLevel(\PacksAnSpielBundle\Entity\Level $level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return \PacksAnSpielBundle\Entity\Level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set location
     *
     * @param \PacksAnSpielBundle\Entity\Location $location
     *
     * @return Game
     */
    public function setLocation(\PacksAnSpielBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \PacksAnSpielBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set status
     *
     * @param int $status
     *
     * @return Game
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
