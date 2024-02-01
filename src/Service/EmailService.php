<?php
    // src/Service/EmailService.php
    namespace App\Service;

    use App\Service\MessageGenerator;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class EmailService
    {
        public function __construct(
            private MessageGenerator $messageGenerator,
            private MailerInterface $mailer,
        ) {
        }

        public function mandaEmail(String $usuario, String $destinatario): bool
        {
            $happyMessage = $this->messageGenerator->getHappyMessage();

            $email = (new Email())
                ->from($usuario)
                ->to($destinatario)
                ->subject('Correo Enviado')
                ->text('Alguien te ha enviado este mensaje: '.$happyMessage);

            $this->mailer->send($email);

            return true;
        }
    }
?>