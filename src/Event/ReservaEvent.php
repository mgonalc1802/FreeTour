<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ReservaEvent extends Event
{
    public const NAME = 'miEvento.reserva';

    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}