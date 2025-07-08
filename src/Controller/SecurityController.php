<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @throws \Exception
     * This actually does nothing this is automatically handled by lexik
     */

    #[Route(path: 'api/login', name: 'app_login',methods:['POST'])]
    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher,
        JWTTokenManagerInterface $jwtManager
    ):JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $email= $data['email'] ?? '';
        $password= $data['password'] ?? '';

        $user=$userRepository->findOneBy(['email'=>$email]);
        if(!$user || !$hasher->isPasswordValid($user,$password)){
            return new JsonResponse(['error'=> 'Invalid credentials'], 401);
        }
        if(! $hasher->isPasswordValid($user,$password)){
            return new JsonResponse(['error'=> 'Invalid credentials'], 401);
        }
        $token = $jwtManager->create($user);

        return new JsonResponse([
            'token' => $token,
            'user'=> [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ]]);

    }

    #[Route(path: '/api/user', name: 'app_user_info', methods: ['GET'])]
    public function getUserInfo(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse([
                'success' => false,
                'message' => 'User not authenticated'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'success' => true,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ]
        ]);
    }

    #[Route(path: 'api/logout', name: 'app_logout',methods:['POST'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
