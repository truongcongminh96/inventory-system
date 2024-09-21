<?php

namespace App\Controller;

use App\Service\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

// {
//     "url": "http://res.cloudinary.com/cld-docs/image/upload/v1719307544/gotjephlnz2jgiu20zni.jpg",
//     "secure_url": "https://res.cloudinary.com/cld-docs/image/upload/v1719307544/gotjephlnz2jgiu20zni.jpg",
//     "asset_folder": "",
//     "api_key": "614335564976464"
//   }

class ItemController extends AbstractController
{
    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    #[Route('/api/upload-image', name: 'upload_image', methods: ['POST'])]
    public function uploadImage(Request $request): JsonResponse
    {
        $itemId = $request->get('item_id');
        $image = $request->files->get('image');

        $response = $this->itemService->handleImageUpload($itemId, $image);
        return new JsonResponse($response['data'], $response['status']);
    }
}
