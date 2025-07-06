<?php
// src/Security/EmailVerifier.php
namespace App\Security;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailVerifier
{
    private MailerInterface $mailer;
    private UrlGeneratorInterface $urlGenerator;
    private string $secret;
    private int $ttl;

    public function __construct(
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator,
        string $signingSecret,
        int $ttl = 3600  // link valid for 1h
    ) {
        $this->mailer       = $mailer;
        $this->urlGenerator = $urlGenerator;
        $this->secret       = $signingSecret;
        $this->ttl          = $ttl;
    }

    public function sendEmailConfirmation(string $routeName, User $user): void
    {
        $expires   = time() + $this->ttl;
        $signature = hash_hmac('sha256', $user->getId() . '|' . $expires, $this->secret);

        $url = $this->urlGenerator->generate(
            $routeName,
            ['id' => $user->getId(), 'expires' => $expires, 'sig' => $signature],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $email = (new TemplatedEmail())
            ->from(new Address('mailer@mydomains.com', 'MailBot'))
            ->to(new Address($user->getEmail(), $user->getFirstName()))
            ->subject('Please confirm your email')
            ->htmlTemplate('registration/confirmation_email.html.twig')
            ->context(['signedUrl' => $url, 'user' => $user])
        ;

        $this->mailer->send($email);
    }

    public function handleEmailConfirmation(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $id      = $request->query->getInt('id');
        $expires = $request->query->getInt('expires');
        $sig     = $request->query->get('sig');

        if (!$id || !$expires || !$sig) {
            throw new HttpException(400, 'Invalid verification link');
        }

        if ($expires < time()) {
            throw new HttpException(400, 'Verification link expired');
        }

        $expected = hash_hmac('sha256', $id . '|' . $expires, $this->secret);
        if (!hash_equals($expected, $sig)) {
            throw new HttpException(400, 'Invalid signature');
        }

        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            throw new HttpException(404, 'User not found');
        }

        $user->setIsVerified(true);
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Email confirmed']);
    }
}
