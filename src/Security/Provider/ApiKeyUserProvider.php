<?php
namespace App\Security\Provider;

use App\Domain\Model\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface
{
    private $userRepository;
    private $user;
     /**
     * UserRepository constructor.
     * @param UserService $userRepository
     */

    public function __construct(UserRepositoryInterface $userRepository){

        $this->userRepository = $userRepository;
    }

    public function getUsernameForApiKey($apiKey) 
    {
        $this->user = $this->userRepository->findByApiKey($apiKey);
        return $this->user;
    }
     
    public function loadUserByUsername($username) 
    {
        
        return new User(
            $username,
            null,
            [$this->user->getRole()]
        );
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class) : bool
    {
        return User::class === $class;
    }
}