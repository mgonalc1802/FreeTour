<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValoracionController extends AbstractController
{
    #[Route('/valoracion', name: 'app_valoracion')]
    public function index(): Response
    {
        return $this->render('valoracion/index.html.twig', [
            'controller_name' => 'ValoracionController',
        ]);
    }
}
