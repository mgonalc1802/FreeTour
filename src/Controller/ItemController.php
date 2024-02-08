<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ItemRepository;


class ItemController extends AbstractController
{
    #[Route('/item', name: 'app_item')]
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }

    #[Route('/API/item/localidad/{localidad}', name: 'itemLocalidad', methods: ["GET"])]
    public function traeItemLocalidad(ItemRepository $itemRepository, $localidad): JsonResponse
    {
        //Obtiene los items de una localidad concreta
        $items = $itemRepository->findByLocalidad($localidad);

        //Genera un array
        $itemArray = [];

        //Bucle que añade datos al array según los datos
        foreach ($items as $item) 
        {
            //Array de Json
            $itemArray[] = $item->jsonSerialize();
        }

        //Devuelve el array en JSON
        return new JsonResponse($itemArray);
    }

    #[Route('/API/item/provincia/{provincia}', name: 'itemProvincia', methods: ["GET"])]
    public function traeItemProvincia(ItemRepository $itemRepository, $provincia): JsonResponse
    {
        //Obtiene los items de una localidad concreta
        $items = $itemRepository->findByProvincia($provincia);

        //Genera un array
        $itemArray = [];

        //Bucle que añade datos al array según los datos
        foreach ($items as $item) 
        {
            //Array de Json
            $itemArray[] = $item->jsonSerialize();
        }

        //Devuelve el array en JSON
        return new JsonResponse($itemArray);
    }
}
