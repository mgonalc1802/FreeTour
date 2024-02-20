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
    public function subirArchivos(Request $request): JsonResponse
    {
        //Indica la ruta de las imágenes
        $to_path = "images/ruta";

        //Mover el archivo
        $archivo = $request->files->get('file');

        //Si archivo no está vacío
        if ($archivo) 
        {
            //Genera la ruta del archivo
            $nuevoArchivo = $to_path . "/" . $archivo->getClientOriginalName();

            //Si se ha movido
            if ($archivo->move($to_path, $archivo->getClientOriginalName())) 
            {
                //Devuelve un true
                return new JsonResponse(["success" => true]);
            } 
            //Si no
            else 
            {
                //Devuelve un false
                return new JsonResponse(["success" => false]);
            }
        }
        //En caso de que esté vacío
        else 
        {
            return new JsonResponse(["success" => false, "error" => "No se ha proporcionado ningún archivo."]);
        }
        
    }

    #[Route('/API/crearRuta', name: "crearRuta", methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $manager, ItemRepository $itemRepository): JsonResponse
    {
        //Obtiene el json enviado
        $data = json_decode($request->getContent(), true);
        
        //Distingue cada atributo del json
        $titulo = $data['titulo'];
        $fechaInicio = $data['fechaInicio'];
        $fechaFin = $data['fechaFin'];
        $aforo = $data['aforo'];
        $descripcion = $data['descripcion'];
        $urlFoto = $data['url_foto'];
        $items = $data['items'];
        $coordenada = $data['coordenadas'];
        $programacion = $data['programacion'];

        //Si están vacíos
        if(empty($titulo) || empty($fechaInicio) || empty($fechaFin) || empty($aforo) || empty($descripcion) || empty($urlFoto) || empty($coordenada))
        {
            //Lanza una excepción
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

        //Llama a la bdd
        $manager->persist($nuevaRuta);

        //Actualiza la bdd
        $manager->flush();

        //Devuelve un json
        return new JsonResponse(['idRuta' => $nuevaRuta->getId()], Response::HTTP_CREATED);
    }
}
