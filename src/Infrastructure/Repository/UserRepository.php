<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserRepository
 * @package App\Infrastructure\Repository
 */
final class UserRepository implements UserRepositoryInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $objectRepository;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(User::class);
    }

    /**
     * @param string $apiKey
     * @return User | null
     */
    public function findByApiKey(string $apiKey): ?User
    {
        return $this->objectRepository->findOneByApiKey($apiKey);
    }

     /**
     * @param string $apiKey
     * @return User | null
     */
    public function login(string $username, string $password): ?User
    {
        return $user =$this->objectRepository->findOneBy(['username'=>$username,'password'=>$password]);
    }

    /**
     * @param string $username
     * @return User | null
     */

    public function findByUsername(string $username): ?User
    {
        return $this->objectRepository->findOneByUsername($username);
    }
    /**
     * @param int $userId
     * @return User
     */
    public function findById(int $userId): ?User
    {
        return $this->objectRepository->find($userId);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->objectRepository->findAll();
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

}