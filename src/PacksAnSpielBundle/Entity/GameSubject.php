<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GameSubject
 *
 * @ORM\Table(name="game_subject")
 * @ORM\Entity(repositoryClass="PacksAnSpielBundle\Repository\GameSubjectRepository")
 */
class GameSubject
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
     * @ORM\Column(name="description", type="string", length=45, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="done_color", type="string", length=45, nullable=true)
     */
    private $doneColor;

    /**
     * @var string
     *
     * @ORM\Column(name="todo_color", type="string", length=45, nullable=true)
     */
    private $todoColor;


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
     * @return GameSubject
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
     * @return GameSubject
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
     * Set todo color
     *
     * @param string $todoColor
     *
     * @return GameSubject
     */
    public function setTodoColor($todoColor)
    {
        $this->todoColor = $todoColor;

        return $this;
    }

    /**
     * Get todoColor
     *
     * @return string
     */
    public function getTodoColor()
    {
        return $this->todoColor;
    }

    /**
     * Set done color
     *
     * @param string $doneColor
     *
     * @return GameSubject
     */
    public function setDoneColor($doneColor)
    {
        $this->doneColor = $doneColor;

        return $this;
    }

    /**
     * Get doneColor
     *
     * @return string
     */
    public function getDoneColor()
    {
        return $this->doneColor;
    }
}
