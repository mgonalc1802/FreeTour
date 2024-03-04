<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            )
            ->setRoles(['ROLE_USER']);

            //Gestionar el archivo
            $file = $form->get('url_foto')->getData();
            if($file)
            {
                // Genera un nombre único para el archivo
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                // Mueve el archivo al directorio donde deseas almacenarlo
                try {
                    $file->move(
                        $this->getParameter('uploads'), // en services.yaml
                        $fileName
                    );
                } 
                catch (FileException $e) 
                {
                    echo "Error.";
                }
            
                $user->setUrlFoto($fileName);
            }


            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('pdf');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    
}
