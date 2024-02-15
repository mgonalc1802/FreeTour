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
    #[Route('/API/usuario/{id}', name: "usuario", methods: ["GET"])]
    public function getUsuario(User $usuario): Response
    {        
        return new JsonResponse($usuario->jsonSerialize());
    }

    #[Route('/API/usuarios', name: "usuarios", methods: ["GET"])]
    public function getUsuarios(UserRepository $userRepository): JsonResponse
    {
        $usuarios = $userRepository->findAll();

        $usuariosArray = [];
        foreach ($usuarios as $usuario) 
        {
            $usuariosArray[] = $usuario->jsonSerialize();
        }

        return new JsonResponse($usuariosArray);
    }

    #[Route('/API/guias', methods: ["GET"])]
    public function getGuias(UserRepository $userRepository): JsonResponse
    {
        $usuarios = $userRepository->findByGuia();

        $usuariosArray = [];
        foreach ($usuarios as $usuario) 
        {
            $usuariosArray[] = $usuario->jsonSerialize();
        }

        return new JsonResponse($usuariosArray);
    }
}
