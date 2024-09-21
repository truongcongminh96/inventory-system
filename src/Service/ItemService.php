<?php

namespace App\Service;

use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Cloudinary\Cloudinary;
use Symfony\Component\HttpFoundation\Response;

class ItemService
{
    private Cloudinary $cloudinary;
    private EntityManagerInterface $entityManagerInterface;
    private ItemRepository $itemRepository;

    public function __construct(EntityManagerInterface $entityManagerInterface, ItemRepository $itemRepository)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->itemRepository = $itemRepository;

        $this->cloudinary = new Cloudinary([
            'cloud_name' => 'symfony',
            'api_key' => '433543459182378',
            'api_secret' => 'KRAyEV26JrFiOMO8SCvccBafac0',
        ]);
    }

    public function handleImageUpload($itemId, $image): array
    {
        $item = $this->itemRepository->find($itemId);

        if (!$item) {
            return [
                'data' => ['error' => 'Item not found'],
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        if (!$image) {
            return [
                'data' => ['error' => 'Image file is required'],
                'status' => Response::HTTP_BAD_REQUEST
            ];
        }

        $isUpdate = $item->getImgUrl() ? true : false;
        $uploadResult = $this->cloudinary->uploadApi()->upload($image->getPathname());
        $imgUrl = $uploadResult['secure_url'];
        $item->setImgUrl($imgUrl);

        $this->entityManagerInterface->persist($item);
        $this->entityManagerInterface->flush();

        return [
            'data' => [
                'message' => $isUpdate ? 'Image updated!!!!!' : 'Image uploaded successfully!!!!!',
                'imgUrl' => $item->getImgUrl()
            ],
            'status' => Response::HTTP_OK
        ];
    }
}
