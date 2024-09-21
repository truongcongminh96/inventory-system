<?php
namespace App\Tests;

use App\Controller\ItemController;
use App\Service\ItemService;
use App\Service\WolfService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ItemControllerTest extends TestCase
{
    public function test_it_upload_image(): void
    {
        $itemServiceMock = $this->createMock(ItemService::class);
        $itemServiceMock->expects($this->once())
            ->method('handleImageUpload')
            ->willReturn(['data' => ['image_url' => 'http://example.com/image.jpg'], 'status' => 200]);

        $wolfServiceMock = $this->createMock(WolfService::class);
        $controller = new ItemController($itemServiceMock, $wolfServiceMock);

        $image = new UploadedFile(
            __DIR__ . '/test-image.jpg',
            'test-image.jpg',
            'image/jpeg',
            null
        );
        $request = new Request([], ['item_id' => 1], [], [], ['image' => $image]);
        $response = $controller->uploadImage($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('http://example.com/image.jpg', $responseData['image_url']);
    }

    public function test_it_update_quality(): void
    {
        $wolfServiceMock = $this->createMock(WolfService::class);
        $wolfServiceMock->expects($this->once())
            ->method('updateQuality');

        $itemServiceMock = $this->createMock(ItemService::class);

        $controller = new ItemController($itemServiceMock, $wolfServiceMock);
        $request = new Request();
        $response = $controller->updateQuality($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Item quality updated', $responseData['message']);
    }
}
