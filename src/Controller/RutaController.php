<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RutaController extends AbstractController
{
    #[Route('/ruta', name: 'app_ruta')]
    public function index(): Response
    {
        return $this->render('ruta/index.html.twig', [
            'controller_name' => 'RutaController',
        ]);
    }
}
