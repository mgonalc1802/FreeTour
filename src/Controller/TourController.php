<?php

namespace App\Controller;

use App\Service\MiEventoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tour;
use App\Entity\Ruta;
use App\Entity\Informe;
use App\Repository\RutaRepository;

class TourController extends AbstractController
{
    #[Route('/tour', name: 'app_tour')]
    public function index(): Response
    {
        return $this->render('tour/index.html.twig', [
            'controller_name' => 'TourController',
        ]);
    }

    // #[Route('/pruebaEventos', name: 'pruebaEventos')]
    // public function triggerEvent(MiEventoService $eventService): Response
    // {
    //     $eventService->triggerMiEvento("Datos específicos.");

    //     return new Response("Evento disparado");
    // }

    #[Route('/API/crearTour', name: "crearTour", methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $manager, RutaRepository $rutaRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $fecha = $data['fecha'];
        $hora = $data['hora'];
        $guia = $data['guia'];
        $idRuta = $data['idRuta'];

        if(empty($fecha) || empty($hora) || empty($guia))
        {
            throw new NotFoundHttpException('No puede haber valores vacíos.');
        }

        //Crea el objeto ruta
        $nuevoTour = new Tour();

        //Bucle que introduce items
        $ruta = $rutaRepository->find($idRuta);

        //Le añade sus propiedades
        $nuevoTour
            ->setFecha(date_create($fecha))
            ->setHora(date_create($hora))
            ->setGuia($guia)
            ->setRutaId($ruta);

        $manager->persist($nuevoTour);
        $manager->flush();

        return new JsonResponse(['idTour' => $nuevoTour->getId()], Response::HTTP_CREATED);
    }
}
