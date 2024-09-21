<?php

namespace App\Service;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Cloudinary\Cloudinary;
use Symfony\Component\HttpFoundation\Response;

class ItemService
{
    private Cloudinary $cloudinary;
    private EntityManagerInterface $entityManagerInterface;
    private ItemRepository $itemRepository;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        ItemRepository $itemRepository,
    ) {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->itemRepository = $itemRepository;

        $this->cloudinary = new Cloudinary([
            'cloud_name' => 'symfony',
            'api_key' => '433543459182378',
            'api_secret' => 'KRAyEV26JrFiOMO8SCvccBafac0',
        ]);
    }

    public function handleImageUpload(int $itemId, $image): array
    {
        $item = $this->itemRepository->find($itemId);

        if (!$item) {
            return $this->createErrorResponse('Item not found', Response::HTTP_NOT_FOUND);
        }

        if (!$image) {
            return $this->createErrorResponse('Missing Image', Response::HTTP_BAD_REQUEST);
        }

        $isUpdate = $item->getImgUrl() !== null;
        $this->uploadImageAndSetUrl($item, $image);

        return $this->createSuccessResponse(
            $isUpdate ? 'Image updated!!!!!' : 'Image uploaded!!!!!',
            $item->getImgUrl()
        );
    }

    private function uploadImageAndSetUrl(Item $item, $image): void
    {
        $uploadResult = $this->cloudinary->uploadApi()->upload($image->getPathname());
        $item->setImgUrl($uploadResult['secure_url']);

        $this->entityManagerInterface->persist($item);
        $this->entityManagerInterface->flush();
    }

    private function createErrorResponse(string $message, int $statusCode): array
    {
        return [
            'data' => ['error' => $message],
            'status' => $statusCode,
        ];
    }

    private function createSuccessResponse(string $message, string $imgUrl): array
    {
        return [
            'data' => [
                'message' => $message,
                'imgUrl' => $imgUrl
            ],
            'status' => Response::HTTP_OK
        ];
    }
}
