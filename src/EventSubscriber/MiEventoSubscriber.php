<?php 

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\MiEvento;

class MiEventoSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            MiEvento::class => "onMiEvento",
        ];
    }

    public function onMiEvento(MiEvento $event)
    {
        $data = $event->getData();

        dump("Se disparó el evento");
    }
}