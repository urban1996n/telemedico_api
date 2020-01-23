<?php

namespace App\Domain\Model\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Domain\Model\User
 */
interface UserRepositoryInterface
{
    /**
     * @param string $apiKey
     * @return User
     */
    
    public function findByApiKey(string $apiKey): ?User;
    /**
     * @param int $userId
     * @return User
     */
    public function findById(int $userId): ?User;

    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param User $user
     */
    public function delete(User $user): void;

     /**
     * @param string $username
     * @return User | null
     */

    public function findByUsername(string $username): ?User;
}