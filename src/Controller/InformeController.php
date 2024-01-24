<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InformeController extends AbstractController
{
    #[Route('/informe', name: 'app_informe')]
    public function index(): Response
    {
        return $this->render('informe/index.html.twig', [
            'controller_name' => 'InformeController',
        ]);
    }
}
