<?php

namespace App\Command;

use App\Entity\Postcode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

class ImportPostcodesCommand extends Command
{
    //This url will be set and pulled from the env
    private const POSTCODES_URL = 'https://data.freemaptools.com/download/uk-outcode-postcodes/postcode-outcodes.csv';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:import-postcodes')
            ->setDescription('Download and import UK postcodes into the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Downloading postcodes data...');

        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', self::POSTCODES_URL);

        if ($response->getStatusCode() !== 200) {
            $output->writeln('Failed to download postcodes data.');

            return Command::FAILURE;
        }

        $postcodesData = $response->getContent();

        // Process the downloaded CSV data and import it into the database
        $rows = explode("\n", $postcodesData);
        foreach ($rows as $row) {
            $data = str_getcsv($row);
            if ($data[0] != 'id' && isset($data[1])) {
                // Assuming the CSV structure: [postcode, latitude, longitude]
                $postcode = new Postcode();
                $postcode->setPostcode($data[1]);
                $postcode->setLatitude(floatval($data[2]));
                $postcode->setLongitude(floatval($data[3]));

                // Persist the postcode entity
                $this->entityManager->persist($postcode);
            }
        }

        // Flush all changes to the database
        $this->entityManager->flush();

        $output->writeln('Postcodes imported successfully.');

        return Command::SUCCESS;
    }
}
