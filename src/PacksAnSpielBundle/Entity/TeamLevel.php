<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * TeamLevel
 *
 * @ORM\Table(name="team_level", indexes={@ORM\Index(name="fk_team_level_team_idx", columns={"team_id"}), @ORM\Index(name="fk_team_level_level1_idx", columns={"level_id"})})
 * @ORM\Entity(repositoryClass="PacksAnSpielBundle\Repository\TeamLevelRepository")
 */
class TeamLevel
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
     * @var \PacksAnSpielBundle\Entity\Level
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\Level")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level_id", referencedColumnName="id")
     * })
     */
    private $level;

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
     * @var Collection
     *
     * @OneToMany(targetEntity="TeamLevelGame", mappedBy="teamLevel")
     */
    private $teamLevelGames;

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
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return TeamLevel
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set finishTime
     *
     * @param \DateTime $finishTime
     *
     * @return TeamLevel
     */
    public function setFinishTime($finishTime)
    {
        $this->finishTime = $finishTime;

        return $this;
    }

    /**
     * Get finishTime
     *
     * @return \DateTime
     */
    public function getFinishTime()
    {
        return $this->finishTime;
    }

    /**
     * Set level
     *
     * @param \PacksAnSpielBundle\Entity\Level $level
     *
     * @return TeamLevel
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
     * Set team
     *
     * @param \PacksAnSpielBundle\Entity\Team $team
     *
     * @return TeamLevel
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


    /**
     *
     * @return Collection<PacksAnSpielBundle\Entity\TeamLevelGame>
     */
    public function getTeamLevelGames()
    {
        return $this->teamLevelGames;
    }

    public function getTeamLevelInfo()
    {
        $gameSubjectInfoList = [];
        $gameSubjectInfoList['status'] = 'open';
        $gameSubjectInfoList['games'] = [];
        $gameSubjectInfoList['level_duration'] = time() - $this->getStartTime();
        /* @var TeamLevelGame $teamLevelGame */
        foreach ($this->getTeamLevelGames() as $teamLevelGame) {
            $gameSubjectInfo = [];
            $gameSubjectInfo['game'] = $teamLevelGame->getAssignedGameSubject();
            if ($teamLevelGame->getFinishTime() != null) {
                $gameSubjectInfo['status'] = 'done';
            } else if ($teamLevelGame->getStartTime() != null && $teamLevelGame->getFinishTime() == null && $teamLevelGame->getAssignedGame() != null) {

                $gameSubjectInfo['status'] = 'running';
                $gameSubjectInfoList['status'] = 'running';
                $gameSubjectInfoList['current_game'] = $teamLevelGame->getAssignedGame();
                $gameSubjectInfoList['current_team_level_game'] = $teamLevelGame;

                $gameSubjectInfoList['game_duration'] = round((time() - $teamLevelGame->getStartTime()) / 60);
            } else {
                $gameSubjectInfo['status'] = 'open';
            }
            $gameSubjectInfoList['games'][] = $gameSubjectInfo;
        }
        return $gameSubjectInfoList;
    }
}
