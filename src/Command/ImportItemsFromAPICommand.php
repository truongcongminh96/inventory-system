<?php

namespace App\Command;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:import-items-from-api',
    description: 'Imports items from API https://api.restful-api.dev/objects'
)]
class ImportItemsFromAPICommand extends Command
{
    public function __construct(
        private readonly HttpClientInterface    $client,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $data = $this->getItems($io);
        if ($data === null) {
            return Command::FAILURE;
        }

        foreach ($data as $itemData) {
            $this->createItem($itemData);
        }

        $this->entityManager->flush();
        $io->success('Items imported successfully!');

        return Command::SUCCESS;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getItems(SymfonyStyle $io): ?array
    {
        $response = $this->client->request('GET', 'https://api.restful-api.dev/objects');
        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            $io->error('Error From https://api.restful-api.dev/objects' . $statusCode);
            return null;
        }

        return $response->toArray();
    }

    private function createItem(array $itemData): void
    {
        $itemRepository = $this->entityManager->getRepository(Item::class);
        $item = $itemRepository->findOneBy(['name' => $itemData['name']]) ?? new Item();

        $item->setName($itemData['name'])
            ->setData($itemData['data'])
            ->setSellIn(rand(1, 30))
            ->setQuality($this->setSpecialQuality($itemData['name']));

        $this->entityManager->persist($item);
    }

    private function setSpecialQuality(string $name): int
    {
        return match (true) {
            $name === 'Samsung Galaxy S23' => 80,
            str_contains($name, 'Apple AirPods') || str_contains($name, 'Apple iPad Air') => rand(20, 50),
            str_contains($name, 'Xiaomi Redmi Note 13') => rand(10, 20),
            default => rand(1, 50),
        };
    }
}
