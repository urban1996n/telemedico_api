<?php

namespace App\Domain\Model\User;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class User
 * @ORM\Entity
 * @package App\Domain\Model\User
 */
class User
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $surname;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $password;
    
     /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $apiKey;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $role;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {

        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {

        $this->name = $name;
    }/**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {

        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @throws \InvalidArgumentException
     */
    public function setPassword(string $password): void
    {
    
        $this->password = hash('sha512',$password);
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {

        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $username
     */
    public function setRole(string $role): void
    {

        $this->role = $role;
    }


}