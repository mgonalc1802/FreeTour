<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\RutaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ruta;
use App\Repository\ItemRepository;

class RutaController extends AbstractController
{
    private $rutaRepository;

    public function __constructor(RutaRepository $rutaRepository)
    {
        $this->rutaRepository = $rutaRepository;
    }

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
        $to_path = "images/ruta";

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

    #[Route('/API/crearRuta', name: "crearRuta", methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $manager, ItemRepository $itemRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $titulo = $data['titulo'];
        $fechaInicio = $data['fechaInicio'];
        $fechaFin = $data['fechaFin'];
        $aforo = $data['aforo'];
        $descripcion = $data['descripcion'];
        $urlFoto = $data['url_foto'];
        $items = $data['items'];
        $coordenada = $data['coordenadas'];
        $programacion = $data['programacion'];

        if(empty($titulo) || empty($fechaInicio) || empty($fechaFin) || empty($aforo) || empty($descripcion) || empty($urlFoto) || empty($coordenada))
        {
            throw new NotFoundHttpException('No puede haber valores vacíos.');
        }

        //Crea el objeto ruta
        $nuevaRuta = new Ruta();

        //Le añade sus propiedades
        $nuevaRuta
            ->settitulo($titulo)
            ->setCoordenadaInicio($coordenada)
            ->setDescripcion($descripcion)
            ->setFechaComienzo(date_create($fechaInicio))
            ->setFechaFin(date_create($fechaFin))
            ->setUrlFoto($urlFoto)
            ->setAforo($aforo)
            ->setProgramacion($programacion);

        //Bucle que introduce items
        for ($i = 0; $i < count($items); $i++)
        {
            $item = $itemRepository->find($items[$i]);
            $nuevaRuta -> addItem($item);
        }

        $manager->persist($nuevaRuta);
        $manager->flush();

        return new JsonResponse(['idRuta' => $nuevaRuta->getId()], Response::HTTP_CREATED);
    }
}
