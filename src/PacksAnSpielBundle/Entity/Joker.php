<?php

namespace PacksAnSpielBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Joker
 *
 * @ORM\Table(name="joker")
 * @ORM\Entity
 */
class Joker
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
     * @var integer
     *
     * @ORM\Column(name="created", type="integer", nullable=true)
     */
    private $created;
    /**
     * @var string
     *
     * @ORM\Column(name="jokercode", type="string", length=32, nullable=true)
     */
    private $jokercode;

    /**
     * @var boolean
     *
     * @ORM\Column(name="joker_used", type="boolean", nullable=true)
     */
    private $jokerUsed;


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
     * Get created
     *
     * @return integer
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param integer $created
     *
     * @return Joker
     */
    public function setCreated($created = false)
    {
        if ($created == false) {
            $created = time();
        }
        $this->created = $created;

        return $this;
    }

    /**
     * Set jokercode
     *
     * @param string $jokercode
     *
     * @return Joker
     */
    public function setJokercode($jokercode)
    {
        $this->jokercode = $jokercode;

        return $this;
    }

    /**
     * Get jokercode
     *
     * @return string
     */
    public function getJokercode()
    {
        return $this->jokercode;
    }

    /**
     * Set jokerUsed
     *
     * @param boolean $jokerUsed
     *
     * @return Joker
     */
    public function setJokerUsed($jokerUsed)
    {
        $this->jokerUsed = $jokerUsed;

        return $this;
    }

    /**
     * Get jokerUsed
     *
     * @return boolean
     */
    public function getJokerUsed()
    {
        return $this->jokerUsed;
    }
}
