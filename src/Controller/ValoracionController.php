<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Valoracion;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\ValoracionType;
use Doctrine\ORM\EntityManagerInterface;


class ValoracionController extends AbstractController
{
    #[Route('/valorarReserva/id={id}', name: 'valorarReserva')]
    public function update(Request $request, Valoracion $valoracion, EntityManagerInterface $entityManager): Response
    {   
        //Crea el formulario
        $form = $this->createForm(ValoracionType::class, $valoracion);
        $form->handleRequest($request);        

        //Si se pulsa el submit
        if ($form->isSubmitted() && $form->isValid())
        {
            //Agrega atributos
            $valoracion->setGuia($form->get('guia')->getData())
                ->setRuta($form->get('ruta')->getData())
                ->setComentarios($form->get('comentarios')->getData());

            //Persiste manualmente la entidad Valoracion
            $entityManager->persist($valoracion);

            //Actualiza los datos en la BDD
            $entityManager->flush();
        }

        //Muestra la 
        return $this->render('valoracion/index.html.twig', [
            "form" => $form
        ]);
    }
}
