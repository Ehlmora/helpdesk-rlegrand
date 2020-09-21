<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailNotification {

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function signup (User $user) {
        $message = (new TemplatedEmail())
            ->from('noreply@localhost.fr')
            ->to($user->getMail())
            ->subject('Votre compte rlegrand.fr')
            ->htmlTemplate('emails/signup.html.twig')
            ->context([
                'user'  => $user,
                'token' => $token
            ])
        ;

        $this->mailer->send($message);
    }
}