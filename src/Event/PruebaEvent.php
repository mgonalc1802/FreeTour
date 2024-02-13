<?php

use Symfony\Contracts\EventDispatcher\Event;

class PruebaEvent extends Event
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
}