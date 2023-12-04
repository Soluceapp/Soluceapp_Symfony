<?php
namespace App\Services;

use App\Entity\Dutil;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService 
{
    public function __construct(private MailerInterface $mailer){}
    public function sendEmail($to='test.test@test.com' ): void
    {
        $email = (new Email())
            ->from('noreply@soluceapp.com')
            ->to($to)
            ->subject('Confirmation d\'inscription chez mooc.soluceapp.com')
            ->text('Confirmation d\'inscription chez mooc.soluceapp.com')
            ->html('registration/confirmation_email.html.twig');

        $this->mailer->send($email);

        // ...
    }
}