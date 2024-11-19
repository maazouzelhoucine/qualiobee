<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ApiPlatform\Core\Annotation\ApiResource;

class RegistrationController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/api/register', name: 'register', methods: ['POST'])]
    #[ApiResource(
        itemOperations: [],
        collectionOperations: [
            'post' => [
                'controller' => self::class . '::register',
                'method' => 'POST',
                'swagger_context' => [
                    'summary' => 'Register a new user',
                    'description' => 'This endpoint registers a new user with email and password.',
                    'responses' => [
                        201 => ['description' => 'User created successfully'],
                        400 => ['description' => 'Invalid input'],
                        409 => ['description' => 'User already exists'],
                    ],
                ],
            ],
        ]
    )]
    public function register(Request $request, UserRepository $userRepository, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['error' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }

        // Check if the user already exists
        $existingUser = $userRepository->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'User already exists'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setEmail($data['email']);

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        // Save the user to the database
        $userRepository->save($user, true);

        return new JsonResponse(['message' => 'User successfully registered'], Response::HTTP_CREATED);
    }
}
