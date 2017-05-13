<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game", indexes={@ORM\Index(name="fk_game_level1_idx", columns={"level_id"}), @ORM\Index(name="fk_game_location1_idx", columns={"location_id"})})
 * @ORM\Entity(repositoryClass="PacksAnSpielBundle\Repository\GameRepository")
 */
class Game
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

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
     * @ORM\Column(name="identifier", type="string", length=45)
     */
    private $identifier;

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
     * @var \PacksAnSpielBundle\Entity\Level
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\Level", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level_id", referencedColumnName="id")
     * })
     */
    private $level;

    /**
     * @var \PacksAnSpielBundle\Entity\Location
     *
     * @ORM\ManyToOne(targetEntity="PacksAnSpielBundle\Entity\Location", fetch="EAGER")
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
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=15, nullable=true)
     */
    private $grade;

    /**
     * @var string
     *
     * @ORM\Column(name="passcode", type="string", length=45, nullable=true)
     */
    private $passcode;

    /**
     * @var string
     */
    private $game_description;

    /**
     * @var string
     *
     * @ORM\Column(name="game_answer", type="string", length=45, nullable=true)
     */
    private $game_answer;

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
     * Set identifier
     *
     * @param string
     *
     * @return Game
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
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
     * Set gameDescription
     *
     * @param string $gameDescription
     *
     * @return Game
     */
    public function setGameDescription($gameDescription)
    {
        $this->game_description = $gameDescription;

        return $this;
    }

    /**
     * Get gameDescription
     *
     * @return string
     */
    public function getGameDescription()
    {
        return $this->game_description;
    }

    /**
     * Set groupTextGameCorrectAnswer
     *
     * @param string $gameAnswer
     *
     * @return Game
     */
    public function setGameAnswer($gameAnswer)
    {
        $this->game_answer = $gameAnswer;

        return $this;
    }

    /**
     * Get groupTextGameCorrectAnswer
     *
     * @return string
     */
    public function getGameAnswer()
    {
        return $this->game_answer;
    }

    /**
     * Set groupTextGameEnd
     *
     * @param string $groupTextGameEnd
     *
     * @return Game
     */
    public function setGroupTextGameEnd($groupTextGameEnd)
    {
        $this->group_text_game_end = $groupTextGameEnd;

        return $this;
    }

    /**
     * Get groupTextGameEnd
     *
     * @return string
     */
    public function getGroupTextGameEnd()
    {
        return $this->group_text_game_end;
    }

    public function getPlayedGames()
    {
        return 1;
    }

    public $activeGames;

    public static function getCorrectLevelGrade($teamGrade, $levelId)
    {
        $grade = "";
        if (in_array($levelId, [1, 2])) {
            $grade = $teamGrade;
        } elseif ($levelId == 3) {
            if (in_array($teamGrade, ['w', 'j'])) {
                $grade = "wj";
            } else {
                $grade = "pr";
            }
        } else {
            $grade = "wjpr";
        }
        return $grade;
    }

}
