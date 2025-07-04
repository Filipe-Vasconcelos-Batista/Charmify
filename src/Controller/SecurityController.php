<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: 'api/login', name: 'app_login',methods:['POST'])]
    public function login(AuthenticationUtils $authenticationUtils):JsonResponse
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            return new JsonResponse([
                'success' => false,
                'message' => $error->getMessageKey(),
                'last_username' => $lastUsername
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ($this->getUser()) {
            return new JsonResponse([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $this->getUser()->getId(),
                    'email' => $this->getUser()->getEmail(),
                ]
            ]);
        }
            return new JsonResponse([
                'success' => false,
                'message' => 'Please provide credentials',
                'last_username' => $lastUsername
            ]);
    }

    #[Route(path: 'api/logout', name: 'app_logout',methods:['POST'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
