<?php
    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Entity\Alumno;
    use App\Repository\RutaRepository;


    //Metalenguaje que ejecuta nuestros ficheros buscando los atributos necesarios      
    class home extends AbstractController
    {
        //Más adelante indicaremos como poner el verbo Get, Post..
        #[Route('/', name: 'home')] 
        public function home(RutaRepository $rutaRepository): Response
        {
            $rutas = $rutaRepository->findAll();

            return $this->render('home.html.twig',
            [
                'rutas' => $rutas
            ]);
        }

        #[Route('/listado', name: 'listado')] 
        public function mostrarTour(): Response
        {
            

            return $this->render('home.html.twig', 
            [
                'alumnos' => $alumnos,
            ]);
        }
    }
?>