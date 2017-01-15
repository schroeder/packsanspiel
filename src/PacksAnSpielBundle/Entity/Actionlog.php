<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actionlog
 *
 * @ORM\Table(name="actionlog", indexes={@ORM\Index(name="fk_actionlog_team1_idx", columns={"team_id"}), @ORM\Index(name="fk_actionlog_game1_idx", columns={"game_id"})})
 * @ORM\Entity
 */
class Actionlog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="log_text", type="string", length=45, nullable=true)
     */
    private $logText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="time", nullable=true)
     */
    private $timestamp;

    /**
     * @var \PacksAnSpielBundle\Entity\Game
     *
     * @ORM\OneToOne(targetEntity="PacksAnSpielBundle\Entity\Game")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="game_id", referencedColumnName="id", unique=true)
     * })
     */
    private $game;

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
     * Set id
     *
     * @param integer $id
     *
     * @return Actionlog
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set logText
     *
     * @param string $logText
     *
     * @return Actionlog
     */
    public function setLogText($logText)
    {
        $this->logText = $logText;

        return $this;
    }

    /**
     * Get logText
     *
     * @return string
     */
    public function getLogText()
    {
        return $this->logText;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Actionlog
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set game
     *
     * @param \PacksAnSpielBundle\Entity\Game $game
     *
     * @return Actionlog
     */
    public function setGame(\PacksAnSpielBundle\Entity\Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \PacksAnSpielBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set team
     *
     * @param \PacksAnSpielBundle\Entity\Team $team
     *
     * @return Actionlog
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
