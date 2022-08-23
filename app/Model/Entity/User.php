<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User
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
     * @var string 
     * 
     * @ORM\Column(name="username", type="string", length=50)
     */
    private $username;


      /**
     * @var string 
     * 
     * @ORM\Column(name="password", type="string", length=500)
     */
    private $password;

    /**
     * @var string 
     * 
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;


    /**
     * @var string 
     * 
     * @ORM\Column(name="activation_code", type="string", length=50, nullable=TRUE)
     */
    private $activation_code;



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
     * Get the value of username
     *
     * @return  string
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param  string  $username
     *
     * @return  self
     */ 
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     *
     * @return  string
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     *
     * @return  self
     */ 
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return  string
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of activation_code
     *
     * @return  string
     */ 
    public function getActivation_code()
    {
        return $this->activation_code;
    }

    /**
     * Set the value of activation_code
     *
     * @param  string  $activation_code
     *
     * @return  self
     */ 
    public function setActivation_code(string $activation_code)
    {
        $this->activation_code = $activation_code;

        return $this;
    }
}