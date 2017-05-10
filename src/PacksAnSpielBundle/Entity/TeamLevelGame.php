<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeamLevelGame
 *
 * @ORM\Table(name="team_level_game", indexes={@ORM\Index(name="fk_team_level_game_team_level1_idx", columns={"team_level_id"}), @ORM\Index(name="fk_team_level_game_game_subject1_idx", columns={"assigned_game_subject"}), @ORM\Index(name="fk_team_level_game_joker1_idx", columns={"used_joker"}), @ORM\Index(name="fk_team_level_game_game1_idx", columns={"assigned_game"})})
 * @ORM\Entity
 */
class TeamLevelGame
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
     * @ORM\Column(name="played_points", type="string", length=45, nullable=true)
     */
    private $playedPoints;

    /**
     * @var int
     *
     * @ORM\Column(name="start_time", type="integer", nullable=true)
     */
    private $startTime;

    /**
     * @var int
     *
     * @ORM\Column(name="finish_time", type="integer", nullable=true)
     */
    private $finishTime;

    /**
     * @var \PacksAnSpielBundle\Entity\Joker
     *
     * @ORM\OneToOne(targetEntity="PacksAnSpielBundle\Entity\Joker")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="used_joker", referencedColumnName="id", unique=true)
     * })
     */
    private $usedJoker;

    /**
     * @var \PacksAnSpielBundle\Entity\Game
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\Game", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="assigned_game", referencedColumnName="id")
     * })
     */
    private $assignedGame;

    /**
     * @var \PacksAnSpielBundle\Entity\GameSubject
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\GameSubject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="assigned_game_subject", referencedColumnName="id")
     * })
     */
    private $assignedGameSubject;

    /**
     * @var \PacksAnSpielBundle\Entity\TeamLevel
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\TeamLevel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_level_id", referencedColumnName="id")
     * })
     */
    private $teamLevel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="solved_by_leveljump", type="boolean", nullable=true)
     */
    private $solvedByLeveljump;

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return TeamLevelGame
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
     * Set playedPoints
     *
     * @param string $playedPoints
     *
     * @return TeamLevelGame
     */
    public function setPlayedPoints($playedPoints)
    {
        $this->playedPoints = $playedPoints;

        return $this;
    }

    /**
     * Get playedPoints
     *
     * @return string
     */
    public function getPlayedPoints()
    {
        return $this->playedPoints;
    }

    /**
     * Set startTime
     *
     * @param string $startTime
     *
     * @return TeamLevelGame
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set finishTime
     *
     * @param string $finishTime
     *
     * @return TeamLevelGame
     */
    public function setFinishTime($finishTime)
    {
        $this->finishTime = $finishTime;

        return $this;
    }

    /**
     * Get finishTime
     *
     * @return string
     */
    public function getFinishTime()
    {
        return $this->finishTime;
    }

    /**
     * Set usedJoker
     *
     * @param \PacksAnSpielBundle\Entity\Joker $usedJoker
     *
     * @return TeamLevelGame
     */
    public function setUsedJoker(\PacksAnSpielBundle\Entity\Joker $usedJoker = null)
    {
        $this->usedJoker = $usedJoker;

        return $this;
    }

    /**
     * Get usedJoker
     *
     * @return \PacksAnSpielBundle\Entity\Joker
     */
    public function getUsedJoker()
    {
        return $this->usedJoker;
    }

    /**
     * Set assignedGame
     *
     * @param \PacksAnSpielBundle\Entity\Game $assignedGame
     *
     * @return TeamLevelGame
     */
    public function setAssignedGame(\PacksAnSpielBundle\Entity\Game $assignedGame = null)
    {
        $this->assignedGame = $assignedGame;

        return $this;
    }

    /**
     * Get assignedGame
     *
     * @return \PacksAnSpielBundle\Entity\Game
     */
    public function getAssignedGame()
    {
        return $this->assignedGame;
    }

    /**
     * Set assignedGameSubject
     *
     * @param \PacksAnSpielBundle\Entity\GameSubject $assignedGameSubject
     *
     * @return TeamLevelGame
     */
    public function setAssignedGameSubject(\PacksAnSpielBundle\Entity\GameSubject $assignedGameSubject = null)
    {
        $this->assignedGameSubject = $assignedGameSubject;

        return $this;
    }

    /**
     * Get assignedGameSubject
     *
     * @return \PacksAnSpielBundle\Entity\GameSubject
     */
    public function getAssignedGameSubject()
    {
        return $this->assignedGameSubject;
    }

    /**
     * Set teamLevel
     *
     * @param \PacksAnSpielBundle\Entity\TeamLevel $teamLevel
     *
     * @return TeamLevelGame
     */
    public function setTeamLevel(\PacksAnSpielBundle\Entity\TeamLevel $teamLevel = null)
    {
        $this->teamLevel = $teamLevel;

        return $this;
    }

    /**
     * Get teamLevel
     *
     * @return \PacksAnSpielBundle\Entity\TeamLevel
     */
    public function getTeamLevel()
    {
        return $this->teamLevel;
    }
}
