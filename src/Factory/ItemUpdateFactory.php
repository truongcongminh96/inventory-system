<?php

namespace App\Factory;

use App\Entity\Item;
use App\Service\Update\AppleAirPodsUpdateService;
use App\Service\Update\AppleIPadAirUpdateService;
use App\Service\Update\CommonItemUpdateService;
use App\Service\Update\SamsungGalaxyS23UpdateService;
use App\Interface\ItemUpdateInterface;

class ItemUpdateFactory
{
    public static function getUpdate(Item $item): ItemUpdateInterface
    {
        return match ($item->getName()) {
            'Apple AirPods' => new AppleAirPodsUpdateService(),
            'Samsung Galaxy S23' => new SamsungGalaxyS23UpdateService(),
            'Apple iPad Air' => new AppleIPadAirUpdateService(),
            default => new CommonItemUpdateService(),
        };
    }
}
