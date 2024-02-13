<?php namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class LoginSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        // Registra el método que se llamará cuando ocurra el evento security.internal
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        // Obtén el usuario que ha iniciado sesión
        $user = $event->getAuthenticationToken()->getUser();

        // Tu lógica, por ejemplo, puedes registrar el inicio de sesión
        // Envía un correo electrónico cada vez que se recibe una solicitud
        $this->sendEmail("Solicitud recibida: " . $user->getNombre());
        
    }

    private function sendEmail($user)
    {
        // Implementa la lógica para enviar el correo electrónico utilizando el servicio Mailer
        $email = (new Email())
            ->from('noreply@example.com')
            ->to('admin@example.com')
            ->subject('Usuario')
            ->text($user);

        $this->mailer->send($email);
    }
}

