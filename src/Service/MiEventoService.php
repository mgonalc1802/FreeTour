<?php 
namespace App\Service;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use App\Event\PruebaEvent;

class MiEventoService 
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function triggerMiEvento($data)
    {
        $event = new PruebaEvent($data);
        $this->eventDispatcher->dispach($event, PruebaEvent::class);
    }
}