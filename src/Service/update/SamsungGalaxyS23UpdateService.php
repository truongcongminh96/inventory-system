<?php

namespace App\Service\Update;

use App\Entity\Item;
use App\Interface\ItemUpdateInterface;

class SamsungGalaxyS23UpdateService implements ItemUpdateInterface
{
    public function update(Item $item): void
    {
        //**"Samsung Galaxy S23"**, being a legendary item, never has to be sold or decreases in Quality
    }
}
