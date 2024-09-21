<?php

namespace App\Tests;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Service\ItemService;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ItemServiceTest extends TestCase
{
    private $entityManagerMock;
    private $itemRepositoryMock;
    private $cloudinaryMock;
    private $uploadApiMock;
    private $itemService;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->itemRepositoryMock = $this->createMock(ItemRepository::class);
        $this->cloudinaryMock = $this->createMock(Cloudinary::class);
        $this->uploadApiMock = $this->createMock(UploadApi::class);

        $this->itemService = new ItemService(
            $this->entityManagerMock,
            $this->itemRepositoryMock
        );

        $this->cloudinaryMock->method('uploadApi')->willReturn($this->uploadApiMock);

        $this->itemService->cloudinary = $this->cloudinaryMock;
    }

    public function test_it_handle_image_upload_item_not_found(): void
    {
        $this->itemRepositoryMock->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $result = $this->itemService->handleImageUpload(1, null);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $result['status']);
        $this->assertEquals('Item not found', $result['data']['error']);
    }

    public function test_it_handle_image_upload_missing_image(): void
    {
        $item = new Item();
        $this->itemRepositoryMock->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($item);

        $result = $this->itemService->handleImageUpload(1, null);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result['status']);
        $this->assertEquals('Missing Image', $result['data']['error']);
    }

    public function test_it_handle_image_upload_success(): void
    {
        //TODO::
        $this->assertEquals('Success', 'Success');
    }
}
