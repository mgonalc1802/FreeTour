<?php 

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Event\PruebaEvent;

class MiEventoSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            MiEvento::class => "onMiEvento",
        ];
    }

    public function onMiEvento(MiEvento $event)
    {
        $data = $event->getData();

        // Implementa la lógica para enviar el correo electrónico utilizando el servicio Mailer
        $email = (new Email())
            ->from('noreply@example.com')
            ->to('admin@example.com')
            ->subject('Nueva Solicitud')
            ->text($message);

        $this->mailer->send($email);

        dump("Se disparó el evento");
    }
}