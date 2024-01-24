<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservaController extends AbstractController
{
    #[Route('/reserva', name: 'app_reserva')]
    public function index(): Response
    {
        return $this->render('reserva/index.html.twig', [
            'controller_name' => 'ReservaController',
        ]);
    }
}
