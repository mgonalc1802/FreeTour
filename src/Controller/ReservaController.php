<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DomCrawler\Crawler;
use App\Form\Type\ReservaType;
use App\Repository\TourRepository;
use App\Entity\{Ruta, Reserva, User, Tour};

class ReservaController extends AbstractController
{
    #[Route('/reserva/id={id}', name: 'crearReserva')]
    public function new(Request $request, Ruta $ruta, TourRepository $tourRepository): Response
    {
        //Crea el objeto reserva
        $reserva = new Reserva();

        //Crea el formualio
        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);

        //Obtiene todos los tours
        $tours = $tourRepository->findByRuta($ruta->getId());

        //Obtiene todos los items
        $items = $ruta->getItem();
        
        //Si se pulsa el submit
        if ($form->isSubmitted() && $form->isValid()) 
        {
            //Renderiza la vista y obtén el contenido HTML
            $htmlContent = $this->renderView('reserva/new.html.twig', [
                'ruta' => $ruta,
                'tours' => $tours,
                'items' => $items,
                'form' => $form
            ]);
            
            $crawler = new Crawler($htmlContent);

            //Obtener el contenido de un elemento
            // $tours = $crawler->filter('.tours option:selected')->html();

            $tour = new Tour($tourRepository->findById("39"));

            //Agrega atributos
            $reserva->setTour($tour)
                    // ->setValoracion('')
                    ->setUsuario($this->getUser())
                    ->setFecha(($ruta->getFechaFin()))
                    ->setHora($tour->getHora())
                    ->setNumeroReservas($form->get('numero_reservas')->getData());
            
            $entityManager->persist($reserva);
            $entityManager->flush();
        }       

        // Obtener el usuario logueado
        // $usuarioLogueado = $this->getUser();

        // echo $usuarioLogueado->getNombre();

        return $this->render('reserva/new.html.twig', [
            'ruta' => $ruta,
            'tours' => $tours,
            'items' => $items,
            'form' => $form
        ]);
    }
}
