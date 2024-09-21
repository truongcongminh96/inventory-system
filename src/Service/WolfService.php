<?php

declare(strict_types=1);

namespace App\Service;

use App\Factory\ItemUpdateFactory;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class WolfService
{
    private ItemRepository $itemRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ItemRepository         $itemRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->itemRepository = $itemRepository;
        $this->entityManager = $entityManager;
    }

    public function updateQuality(): void
    {
        $items = $this->itemRepository->findAll();

        foreach ($items as $item) {
            $update = ItemUpdateFactory::getUpdate($item);
            $update->update($item);
        }

        $this->entityManager->flush();
    }
}
