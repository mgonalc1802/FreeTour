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
use App\Repository\TourRepository;

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
        //Guarda los datos json que vienen
        $data = json_decode($request->getContent(), true);

        //Obtiene cada atributo incluido del json
        $fecha = $data['fecha']; //Fecha
        $hora = $data['hora']; //Hora
        $guia = $data['guia']; //Guia
        $idRuta = $data['idRuta']; //IdRuta

        //Comprueba que no están vacíos
        if(empty($fecha) || empty($hora) || empty($guia))
        {
            //Devuelve una excepción
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

        //Genera el persist
        $manager->persist($nuevoTour);

        //Lo inserta en la bdd
        $manager->flush(); 

        //Devuelve la id del archivo recién creado
        return new JsonResponse(['idTour' => $nuevoTour->getId()], Response::HTTP_CREATED);
    }

    #[Route('/API/getTours', name: "getTours", methods: ['GET'])]
    public function getTour(TourRepository $tourRepository, RutaRepository $rutaRepository): JsonResponse
    {
        //Trae todos los tours
        $tours = $tourRepository->findAll();

        //Crea un array
        $toursArray = [];

        //Reccorre los tours
        foreach ($tours as $tour) 
        {
            //Añade al array cada tour serializado
            $toursArray[] = $tour->jsonSerialize();
        }

        //Devuelve el array con tours
        return new JsonResponse($toursArray);
    }

    #[Route('/API/modificarTour', name: "modificarTour", methods: ['POST'])]
    public function update(Request $request, EntityManagerInterface $manager, TourRepository $tourRepository): JsonResponse
    {
        //Obtiene el json enviado
        $data = json_decode($request->getContent(), true);
        
        //Distingue cada atributo del json
        $id = $data['id'];
        $guia = $data['guia'];

        //Busca el tour que se va a actualizar
        $tourActualizado = $tourRepository->findById($id);

        //Le añade sus propiedades
        $tourActualizado[0]->setGuia($guia);

         //Llama a la bdd
         $manager->persist($tourActualizado[0]);

         //Actualiza la bdd
         $manager->flush();
 
         //Devuelve un json
         return new JsonResponse(['Tour Actualizado. ID: ' => $tourActualizado[0]->getId()], Response::HTTP_CREATED);

    }

}
