<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{

    public function __construct(private EmailVerifier $emailVerifier)
    {

    }

    #[Route('api/register', name: 'app_register',methods:['POST'])]
    public function register(Request $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $entityManager): JsonResponse
    {
        $data= json_decode($request->getContent(),true);
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true, true) as $error) {
                $errors[] = $error->getMessage();
            }
            return new JsonResponse([
                'success' => false,
                'errors' => $errors,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $plainPassword=$form->get('plainPassword')->getData();
        $user->setPassword(
            $userPasswordHasher->hashPassword($user,$plainPassword));
        $user->setIsSystemEmail(false);
        $user->setAcceptedTermsAt(new \DateTimeImmutable());
        $entityManager->persist($user);
        $entityManager->flush();

        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->to($user->getEmail())
                ->from( 'mailer@mydomains.com')
                ->subject('Confirmation de votre email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
            );
        return new JsonResponse([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => [
                'email' => $user->getEmail(),
                'id' => $user->getId(),
                ],
            ], Response::HTTP_CREATED);
    }

    #[Route('api/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager): jsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $entityManager);
        } catch (Exception $exception) {
            return new JsonResponse([
                'success' => true,
                'message' => 'verify_email_error', $translator->trans($exception->getMessage(), [], 'VerifyEmailBundle')
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Email verified successfully'
            ]);
    }
}
