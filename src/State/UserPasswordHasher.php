<?php
// api/src/State/UserPasswordHasher.php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User; // Import the correct User entity

/**
 * @implements ProcessorInterface<User, User|void>
 */
final class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * @param User $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        if (!$data->getPlainPassword()) {
            // If no password to hash, delegate to the next processor
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword(
            $data,
            $data->getPlainPassword()
        );
        $data->setPassword($hashedPassword);

        // Erase plaintext password after hashing for security
        $data->eraseCredentials();

        // Call the next processor in the chain
        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
