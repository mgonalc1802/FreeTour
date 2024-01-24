<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocalidadController extends AbstractController
{
    #[Route('/localidad', name: 'app_localidad')]
    public function index(): Response
    {
        return $this->render('localidad/index.html.twig', [
            'controller_name' => 'LocalidadController',
        ]);
    }
}
