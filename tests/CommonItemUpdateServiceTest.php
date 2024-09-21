<?php

namespace App\Tests;

use App\Entity\Item;
use App\Service\Update\CommonItemUpdateService;
use PHPUnit\Framework\TestCase;

class CommonItemUpdateServiceTest extends TestCase
{
    private CommonItemUpdateService $updateService;

    protected function setUp(): void
    {
        $this->updateService = new CommonItemUpdateService();
    }

    public function test_it_quality_decreases_before_sell_in_date()
    {
        $item = $this->createMock(Item::class);
        $item->method('getSellIn')->willReturn(10);
        $item->method('getQuality')->willReturn(20);

        $item->expects($this->once())->method('setSellIn')->with(9);
        $item->expects($this->once())->method('setQuality')->with(19);

        $this->updateService->update($item);
    }

    public function test_it_quality_does_no_below_zero()
    {
        $item = $this->createMock(Item::class);
        $item->method('getSellIn')->willReturn(5);
        $item->method('getQuality')->willReturn(0);

        $item->expects($this->once())->method('setSellIn')->with(4);
        $item->expects($this->never())->method('setQuality');

        $this->updateService->update($item);
    }

    public function test_it_sell_in_decreases_each_day()
    {
        $item = $this->createMock(Item::class);
        $item->method('getSellIn')->willReturn(5);
        $item->method('getQuality')->willReturn(10);

        $item->expects($this->once())->method('setSellIn')->with(4);
        $item->expects($this->once())->method('setQuality')->with(9);

        $this->updateService->update($item);
    }
}
