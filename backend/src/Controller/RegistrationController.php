<?php

// src/Controller/ApiRegistrationController.php
namespace App\Controller;

use App\Entity\User;
use App\Service\ApiResponseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        ApiResponseService $apiResponse,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ): JsonResponse 
    {
        // Получаем данные из запроса
        $data = json_decode($request->getContent(), true);
        // Проверка, что данные были переданы
        if (empty($data['email']) || empty($data['password'])) {
            return $apiResponse->error('Email and password are required.', 400);
        }

        // Создаем нового пользователя
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

        // Валидация данных
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $apiResponse->error('Validation failed.', 400, $errorMessages);
        }

        // Проверка, что email уникален
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return $apiResponse->error('Email is already in use.', 400);
        }

        // Сохраняем пользователя в базе данных
        $entityManager->persist($user);
        $entityManager->flush();

        // Возвращаем успешный ответ
        return $apiResponse->success(['message' => 'User successfully created!'], 201);
    }
}
