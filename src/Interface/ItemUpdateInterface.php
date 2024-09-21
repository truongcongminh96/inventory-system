<?php

namespace App\Interface;

use App\Entity\Item;

interface ItemUpdateInterface
{
    public function update(Item $item): void;
}
