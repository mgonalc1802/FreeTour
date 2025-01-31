<?php

// src/Controller/MailerController.php
namespace App\Controller;

use App\Service\MessageGenerator;
use App\Service\EmailService;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('mariadoloresga18@gmail.com')
            ->to('mariadoloresga18@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Prueba de Symfony')
            ->text('Enviar gmail')
            ->html('<p>Debería de enviar un gmail</p>');

        $mailer->send($email);

        return new Response('Correo enviado.');
    }

    #[Route("/enviarEmail/{id}", name: 'enviarEmail')]
    public function new(User $user, EmailService $EmailService): Response
    {

        if ($EmailService->mandaEmail("mara@gmail.com", $user->getEmail())) 
        {
            $this->addFlash('success', 'Notification mail was sent successfully.');
        }

        return new Response('Site Enviado');

    }
}

?>