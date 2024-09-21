<?php

namespace App\Service\Update;

use App\Entity\Item;
use App\Interface\ItemUpdateInterface;

class AppleAirPodsUpdateService implements ItemUpdateInterface
{
    public function update(Item $item): void
    {
        if ($item->getQuality() < 50) {
            $item->setQuality($item->getQuality() + 1);
        }

        $item->setSellIn($item->getSellIn() - 1);

        if ($item->getSellIn() < 0 && $item->getQuality() < 50) {
            $item->setQuality($item->getQuality() + 1);
        }
    }
}
