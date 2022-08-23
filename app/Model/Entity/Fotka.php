<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="fotka")
 */
class Fotka
{
    
    /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private  $id;

    /**
     * @ORM\Column(type="string", length=300)
    */
    private  $filename;

    /**
     * @var int
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", onDelete="RESTRICT")
     */
    private $id_user;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Album")
     * @ORM\JoinColumn(name="id_album", referencedColumnName="id", onDelete="SET NULL")
    */
    private $id_album;



   public function __construct()
   {
       
   }


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
     * Get the value of filename
     */ 
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @return  self
     */ 
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get many features have one product. This is the owning side.
     *
     * @return  int
     */ 
    public function getId_user()
    {
        return $this->id_user;
    }

    /**
     * Set many features have one product. This is the owning side.
     *
     * @param  int  $id_user  Many features have one product. This is the owning side.
     *
     * @return  self
     */ 
    public function setId_user($id_user)
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * Get the value of id_album
     *
     * @return  int
     */ 
    public function getId_album()
    {
        return $this->id_album;
    }

    /**
     * Set the value of id_album
     *
     * @param  int  $id_album
     *
     * @return  self
     */ 
    public function setId_album($id_album)
    {
        $this->id_album = $id_album;

        return $this;
    }
}