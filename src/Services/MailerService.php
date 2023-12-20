<?php
namespace App\Services;

use App\Entity\Dutil;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerService 
{
    public function __construct(private MailerInterface $mailer){$this->mailer=$mailer;}
    public function sendEmail(string $to, array $context ): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@soluceapp.com')
            ->to($to)
            ->subject('Demande de confirmation d\'inscription chez mooc.soluceapp.com')
            ->text('Demande de confirmation d\'inscription chez mooc.soluceapp.com')
            ->htmlTemplate('registration/confirmation_email.html.twig')
            ->context($context);

        $this->mailer->send($email);

        // ...
    }
}