<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="album")
 */
class Album
{

    /**
    * @var int
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\OneToMany(targetEntity="Fotka", mappedBy="Album")
    */
    private $id;

    /**
     * @var string 
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name; 

    /**
     * @var datetime
     * @ORM\Column(name="date", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $date;

    /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userID", referencedColumnName="id", onDelete="CASCADE")
     */
    private $userID;




    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of date
     *
     * @return  datetime
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @param  datetime  $date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of userID
     *
     * @return  int
     */ 
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * Set the value of userID
     *
     * @param  int  $userID
     *
     * @return  self
     */ 
    public function setUserID($userID)
    {
        $this->userID = $userID;

        return $this;
    }
}