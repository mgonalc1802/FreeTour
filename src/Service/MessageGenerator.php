<?php
    namespace App\Service;

    class MessageGenerator
    {
        public function getHappyMessage(): string
        {
            $messages = [
                'Esto es un mensaje que enviaremos',
                'Cuando nos pidan enviar un mensaje feliz',
                '¡Buen Trabajo!',
            ];

            $index = array_rand($messages);

            return $messages[$index];
        }
    }
?>