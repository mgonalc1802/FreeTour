<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DomCrawler\Crawler;
use App\Form\Type\ReservaType;
use App\Repository\{TourRepository, ValoracionRepository, ReservaRepository};
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Ruta, Reserva, User, Tour, Valoracion};

class ReservaController extends AbstractController
{
    #[Route('/reserva/id={id}', name: 'crearReserva')]
    public function new(ReservaRepository $reservaRepository, Request $request, Ruta $ruta, TourRepository $tourRepository, EntityManagerInterface $entityManager, ValoracionRepository $valoraRepository): Response
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

        //Si se pulsa el submit pero no está validado
        if ($form->isSubmitted() && !$form->isValid())
        {
            return $this->render('reserva/new.html.twig', [
                'ruta' => $ruta,
                'tours' => $tours,
                'items' => $items,
                'form' => $form,
                'mostrarAforo' => false,
                'mostrarPersonas' => true,
                'mostrarError' => false
            ]);
        }

        //Si se pulsa el submit y el formulario está correcto
        if ($form->isSubmitted() && $form->isValid())
        {
            //Obtiene las reserva del tour
            $tour = $form->get('tour')->getData();

            //Obtiene las reservas del usuario
            $reservasUser = $this->getUser()->getReservas();

            //Se genera una variable booleana
            $valor = false;

            //Recorre las reservas del usuario 
            foreach ($reservasUser as $reservaUser) 
            {
                //Compara cada reserva con la que se quiere realizar
                if($reservaUser->getTour()->getFecha()->format('d-m-Y') == $tour->getFecha()->format('d-m-Y'))
                {
                    //Se indica que ha entrado
                    $valor = true;
                }
            }

            //Obtiene todas las reservas del tour
            $reservas = $reservaRepository->findByIdTour($tour);

            //Genera el número de reservas
            $numeroReservas = 0;

            //Recorre todas las reservas del tour
            foreach ($reservas as $reserva) 
            {
                //Guarda el numero de reservas que hay
                $numeroReservas = $numeroReservas + $reserva->getNumeroReservas();
            }

            //Si se pasa del aforo
            if($numeroReservas >= $ruta->getAforo())
            {
                //Muestra mensaje error
                return $this->render('reserva/new.html.twig', [
                    'ruta' => $ruta,
                    'tours' => $tours,
                    'items' => $items,
                    'form' => $form,
                    'mostrarAforo' => true,
                    'mostrarPersonas' => false,
                    'mostrarError' => false
                ]);
            }
            else
            {
                //Si el tour ya ha sido reservado por ese usuario
                if($valor == true)
                {
                    //Muestra mensaje de error
                    return $this->render('reserva/new.html.twig', [
                        'ruta' => $ruta,
                        'tours' => $tours,
                        'items' => $items,
                        'form' => $form,
                        'mostrarAforo' => false,
                        'mostrarPersonas' => false,
                        'mostrarError' => true
                    ]);
                }
                else //Si está todo correcto
                {
                    //Crea una reserva para introducir
                    $reservaInsertar = new Reserva();

                    //Crea una valoración nueva
                    $valoracion = new Valoracion();

                    //Añade datos a la valoración
                    $valoracion->setGuia(-1);
                    $valoracion->setRuta(-1);

                    //Genera la fecha actual
                    $fechaActual = new \DateTime();

                    //Agrega atributos
                    $reservaInsertar->setTour($form->get('tour')->getData())
                        ->setValoracion($valoracion)
                        ->setUsuario($this->getUser())
                        ->setFecha($fechaActual)
                        ->setHora($fechaActual)
                        ->setNumeroReservas($form->get('numero_reservas')->getData());

                    //Obtén la entidad Tour desde Reserva
                    $tour = $reservaInsertar->getTour();

                    //Persiste manualmente la entidad Tour
                    // $entityManager->persist($tour);

                    $entityManager->persist($reservaInsertar);
                    $entityManager->flush();
                }
                
            }            
        }

        return $this->render('reserva/new.html.twig', [
            'ruta' => $ruta,
            'tours' => $tours,
            'items' => $items,
            'form' => $form,
            'mostrarAforo' => false,
            'mostrarPersonas' => false,
            'mostrarError' => false
        ]);
    }

    #[Route('/misReservas', name: 'misReservas')]
    public function actions(ReservaRepository $reservaRepository, TourRepository $tourRepository): Response
    {
        //Obtiene la id del usuario
        $idUser = $this->getUser()->getId();
        $misReservas = $reservaRepository->findByIdUser($idUser);

        //Recorre mis reservas
        if(empty($misReservas))
        {
            $error = "No tienes reservas.";
            return $this->render('reserva/error.html.twig', [
                'error' => $error
            ]);
        }
        else
        {
            foreach ($misReservas as &$miReserva) 
            {
                //Obtiene el id del tour
                $idsTour[] = $miReserva->getTour()->getId();
            }

            //Obtiene el
            return $this->render('reserva/show.html.twig', [
                'reservas' => $misReservas
            ]);
        }        
    }

    #[Route('/modificarReserva/{id}', name: 'modificarReserva')]
    public function modificarReserva(EntityManagerInterface $entityManager, Request $request, Reserva $reserva): Response
    {
        //Si reserva está vacío
        if (!$reserva) 
        {
            //Lanza una excepción
            throw $this->createNotFoundException('No se encontró la reserva ');
        }

        //Si se obtiene una respuesta con el método POST
        if ($request->isMethod('POST')) 
        {
            //Obtiene el nuevo valor
            $nuevaCantidadPersonas = $request->request->get('personas');

            //Lo cambia en la reserva
            $reserva->setNumeroReservas($nuevaCantidadPersonas);

            //Actualiza en la bdd
            $entityManager->flush();

            //Actualiza la página
            return $this->redirectToRoute('misReservas');
        }
    }

    #[Route('/cancelarReserva/{id}', name: 'cancelarReserva')]
    public function cancelarReserva(EntityManagerInterface $entityManager, Request $request, Reserva $reserva): Response
    {
        //Si reserva está vacío
        if (!$reserva) 
        {
            //Lanza una excepción
            throw $this->createNotFoundException('No se encontró la reserva');
        }

        //Si se obtiene una respuesta con el método POST
        if ($request->isMethod('POST')) 
        {
            //Eliminar la reserva
            $entityManager->remove($reserva);

            //Actualiza la bdd
            $entityManager->flush();

            //Actualiza la página
            return $this->redirectToRoute('misReservas');
        }

        return $this->render('reservas/show.html.twig', [
            'reserva' => $reserva,
        ]);
    }
}
