<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ApiResponseService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager,
        ApiResponseService $apiResponse
    ) {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return $apiResponse->error('Email and password are required.', 400);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            return $apiResponse->error('Invalid credentials.', 401);
        }

        // Генерация JWT токена
        $token = $jwtManager->create($user);

        return $apiResponse->success([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ]
        ]);
    }
}
