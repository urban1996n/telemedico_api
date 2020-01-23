<?php

namespace App\Application\Service;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
    
/**
 * Class UserService
 * @package App\Application\Service
 */
final class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository){
        $this->userRepository = $userRepository;
    }

    /**
     * Gets users resource by user's ID
     * @param int $userId
     * @return User
     * @throws EntityNotFoundException
     */
    public function getUser(int $userId): User
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User has not been found');
        }
        return $user;
    }

    /**
     * Gets users resource by user's username
     * @param string $userName
     * @return User OR NULL
     * @throws EntityNotFoundException
     */
    public function getUserByUsername(string $userName): bool 
    {
        $user = $this->userRepository->findByUsername($userName);
        if($user != null){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @param string $apiKey
     * @return User
     * @throws EntityNotFoundException
     */
    public function getUserForApiKey(string $apiKey): User
    {
        $user = $this->userRepository->findByApiKey($apiKey);
        if (!$user) {
            throw new EntityNotFoundException('User has not been found');
        }
        return $user;
    }

    
    /**
     * Gets users resource by user's apiKey
     * @param string $apiKey
     * @return User
     * @throws EntityNotFoundException | 
     */
    public function login(string $username , string $password): User
    {
        $password = hash('sha512',$password);
        $user = $this->userRepository->login($username,$password);
        if (!$user) {
            throw new EntityNotFoundException('Invalid credentials');
        }
        return $user;
    }

    /**
     * used for additional user's authentication for it's self management
     * @param string $apiKey
     * @return User
     * @throws EntityNotFoundException | 
     */
    public function authenticate(string $apiKey, string $username , string $password): User
    {
        $user = $this->userRepository->findByApiKey($apiKey);

        if (!$user) {
            throw new EntityNotFoundException('User has not been found');
        }

        if($user->getApiKey() !==$apiKey || $user->getUsername() !==$username || $user->getPassword() !== hash('sha512',$password )){
            throw new BadCredentialsException("Given credentials does not match any Account. Check it before you try again.");
        }
        
        return $user;
    }

    /**
     * lists all users
     * @return array|null
     */
    public function getAllUsers(): ?array
    {
        return $this->userRepository->findAll();
    }

    /**
     * creates user's account
     * @param string $title
     * @param string $content
     * @return User
     */
    public function addUser(string $username, string $name, string $surname, string $password, string $role): User
    {
        $apiKey = substr(str_shuffle(MD5(microtime())), 0, 10);
        $user = new User();
        
        if($this->userRepository->findByUsername($username) !== null){
            throw new \InvalidArgumentException("Username with name {$username} already exists. Pick another username");            
        }

        $user->setUsername($username);
        $user->setName($name);
        $user->setSurname($surname);
        $user->setPassword($password);
        $user->setRole($role);
        $user->setApiKey($apiKey);
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * updating user's details
     * @param int $userId
     * @param string $title
     * @param string $content
     * @return User
     * @throws EntityNotFoundException
     */
    public function updateUser(int $userId, string $username, string $name, string $surname,string $password=null, string $role ): User
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User has not been found');            
        }
        if($username !== null){
            $user->setUsername($username);
        }
        if($password !== null){
            $user->setPassword($password);
        }
        if($role !== null){        
            $user->setRole($role);
        }
        if($name !== null){        
            $user->setName($name);
        }
        if($surname !== null){        
            $user->setSurname($surname);
        }
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * deleting user's account
     * @param int $userId
     * @throws EntityNotFoundException
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User has not been found');            
        }

        $this->userRepository->delete($user);
    }

}