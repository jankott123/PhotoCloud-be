<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="useralb", 
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="neco", columns={"id_user", "id_album"})
 *    }
 * )
 */
class UserAlb
{

    /**
    * @var int
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;

    /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $id_user;

    /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="Album")
     * @ORM\JoinColumn(name="id_album", referencedColumnName="id", onDelete="CASCADE")
     */
    private $id_album;


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
     * Get the value of id_user
     *
     * @return  int
     */ 
    public function getId_user()
    {
        return $this->id_user;
    }

    /**
     * Set the value of id_user
     *
     * @param  int  $id_user
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