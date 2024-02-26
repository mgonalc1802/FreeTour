<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\{TourRepository, RutaRepository, ReservaRepository};
use App\Entity\{Ruta, Tour, Informe};
use App\Form\Type\InformeType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class InformeController extends AbstractController
{
    #[Route('/informe', name: 'app_informe')]
    public function index(): Response
    {
        return $this->render('informe/index.html.twig', [
            'controller_name' => 'InformeController',
        ]);
    }

    #[Route('/misTours', name: 'misTours')]
    public function show(TourRepository $tourRepository, RutaRepository $rutaRepository): Response
    {
        //Obtiene el nombre del usuario logueado
        $nombreUser = $this->getUser()->getNombre();

        //Obtiene el apellido del usuario logueado
        $apellidoUser = $this->getUser()->getApellido();

        //Obtiene los tour de ese usuario/guia
        $tours = $tourRepository->findByGuia($nombreUser, $apellidoUser);

        //Bucle que guarda los titulos de llas rutas
        foreach ($tours as &$tour) 
        {
            //Obtiene el id del tour
            $rutas = $rutaRepository->findById($tour->getRutaId());
        }

        return $this->render('informe/verRutas.html.twig',
        [
            'rutas' => $rutas,
            'tours' => $tours
        ]);
    }
    

    #[Route('/misTours/id={id}', name: 'verTours')]
    public function verTours(Request $request, Ruta $ruta, TourRepository $tourRepository, EntityManagerInterface $entityManager): Response
    {
        //Obtiene el nombre del usuario logueado
        $nombreUser = $this->getUser()->getNombre();

        //Obtiene el apellido del usuario logueado
        $apellidoUser = $this->getUser()->getApellido();

        //Obtiene los tour de ese usuario/guia
        $tours = $tourRepository->findByRutaGuia($ruta, $nombreUser, $apellidoUser);

        return $this->render('informe/verTours.html.twig', [
            'ruta' => $ruta,
            'tours' => $tours
        ]);
    }

    #[Route('/informe/id={id}', name: 'crearInforme')]
    public function new(Request $request, Tour $tour, EntityManagerInterface $entityManager, ReservaRepository $reservaRepository): Response
    {
        //Crea el objeto reserva
        $informe = new Informe();

        //Crea el formulario
        $form = $this->createForm(InformeType::class, $informe);
        $form->handleRequest($request);

        

        //Si se pulsa el submit
        if ($form->isSubmitted() && $form->isValid())
        {
            if($tour->getInforme =! null)
            {
               echo '<script>alert("Ya has realizado un informe para este tour.");</script>)';
            }
            else
            {
                //Gestionar el archivo
                $file = $form->get('foto_grupal')->getData();

                if($file)
                {
                    //Genera un nombre único para el archivo
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                    //Mueve el archivo al directorio donde deseas almacenarlo
                    try {
                        $file->move(
                            $this->getParameter('informes'), //en services.yaml
                            $fileName
                        );
                    } 
                    catch (FileException $e) 
                    {
                        echo "Error";
                    }
                    
                    //Indica la ruta en el atributo FotoGrupal
                    $informe->setFotoGrupal($fileName);
                }

                //Agrega atributos
                $informe->setParticipantes($form->get('participantes')->getData())
                    ->setDineroRecaudado($form->get('dinero_recaudado')->getData())
                    ->setDescripcion($form->get('descripcion')->getData());

                //Persiste manualmente la entidad Infomre
                $entityManager->persist($informe);

                //Guarda en tour el nuevo informe
                $tour->setInforme($informe);

                //Persiste manualmente la entidad Tour
                $entityManager->persist($tour);

                //Actualiza los datos en la BDD
                $entityManager->flush();
            }
            
           
        }

        //Reservas de los tours
        $participantes = $reservaRepository->findByIdTour($tour);

        //Genera la plantilla
        return $this->render('informe/new.html.twig', [
            'form' => $form,
            'personas' => $participantes
        ]);
    }
}
