<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DomCrawler\Crawler;
use App\Form\Type\ReservaType;
use App\Repository\TourRepository;
use App\Repository\ValoracionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Ruta, Reserva, User, Tour, Valoracion};

class ReservaController extends AbstractController
{
    #[Route('/reserva/id={id}', name: 'crearReserva')]
    public function new(Request $request, Ruta $ruta, TourRepository $tourRepository, EntityManagerInterface $entityManager, ValoracionRepository $valoraRepository): Response
    {
        //Crea el objeto reserva
        $reserva = new Reserva();

        //Crea el formulario
        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);

        //Obtiene todos los tours
        $tours = $tourRepository->findByRuta($ruta->getId());

        //Obtiene todos los items
        $items = $ruta->getItem();
        
        //Si se pulsa el submit
        if ($form->isSubmitted() && $form->isValid()) 
        {
            //INTENTAR OBTENER A TRAVÉS DEL GETDATA LO DE FORMULARIO
            // var_dump($form->getData());
            //Renderiza la vista y obtén el contenido HTML
            // $htmlContent = $this->renderView('reserva/new.html.twig', [
            //     'ruta' => $ruta,
            //     'tours' => $tours,
            //     'items' => $items,
            //     'form' => $form
            // ]);
            
            // $crawler = new Crawler($htmlContent);

            //Obtener el contenido de un elemento
            // $tours = $crawler->filter('.tours option:selected')->html();

            $tour = new Tour($tourRepository->findById("39"));

            //Crea una valoración nueva
            $valoracion = new Valoracion();

            //Añade datos a la valoración
            $valoracion->setGuia(-1);
            $valoracion->setRuta(-1);
            
            //Genera la fecha actual
            $fechaActual = new \DateTime();

            //Agrega atributos
            $reserva->setTour($form->get('tour')->getData())
                ->setValoracion($valoracion)
                ->setUsuario($this->getUser())
                ->setFecha($fechaActual)
                ->setHora($fechaActual)
                ->setNumeroReservas($form->get('numero_reservas')->getData());
            
            //Obtén la entidad Tour desde Reserva
            $tour = $reserva->getTour(); 

            //Persiste manualmente la entidad Tour
            $entityManager->persist($tour);

            $entityManager->persist($reserva);
            $entityManager->flush();
        }

        return $this->render('reserva/new.html.twig', [
            'ruta' => $ruta,
            'tours' => $tours,
            'items' => $items,
            'form' => $form
        ]);
    }
}
