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
use App\Repository\UserRepository;


class UsuarioController extends AbstractController
{
    #[Route('/api/usuario/{id}',  methods: ["GET"])]
    public function getUsuario(User $usuario): Response
    {

        // TODO query the database
        $user = 
        [
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombre(),
            'apellido' => $usuario->getApellido(),
            'apellido2' => $usuario->getApellido2(),
            'roles' => $usuario->getRoles(),
            'url_foto' => "url_foto"
        ];
        
        return new JsonResponse($user);
    }

    #[Route('/api/usuarios',  methods: ["GET"])]
    public function getUsuarios(UserRepository $userRep): Response
    {
        $usuarios = $userRep->findAll();
        
        return new JsonResponse($usuarios);
    }
}
