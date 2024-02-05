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

    #[Route('/API/subirArchivos', name: "subirArchivos", methods: ['POST'])]
    public function subirArchivos()
    {
        //Definiciones
        $to_path = "images/items";

        //Mover el archivo
        $nuevoArchivo = $to_path . "/" . $_FILES['file']['name'];
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $nuevoArchivo)) 
        {
            return json_encode(["success" => true]);
        }
        else
        {
            return json_encode(["success" => false]);
        }


    }
    

}
