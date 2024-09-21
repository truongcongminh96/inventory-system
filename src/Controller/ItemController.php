<?php

namespace App\Controller;

use App\Service\ItemService;
use App\Service\WolfService;
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
    private WolfService $wolfService;

    public function __construct(ItemService $itemService, WolfService $wolfService)
    {
        $this->itemService = $itemService;
        $this->wolfService = $wolfService;
    }

    #[Route('/api/upload-image', name: 'upload_image', methods: ['POST'])]
    public function uploadImage(Request $request): JsonResponse
    {
        $itemId = (int)$request->get('item_id');
        $image = $request->files->get('image');

        $response = $this->itemService->handleImageUpload($itemId, $image);

        return new JsonResponse($response['data'], $response['status']);
    }

    #[Route('/api/update-quality', name: 'update_quality', methods: ['POST'])]
    public function updateQuality(Request $request): JsonResponse
    {
        $this->wolfService->updateQuality();

        return new JsonResponse(['status' => 'success', 'message' => 'Item quality updated']);
    }
}
