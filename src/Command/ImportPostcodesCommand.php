<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Postcode;

class ImportPostcodesCommand extends Command
{
    //This url will be set and pulled from the env
    private const POSTCODES_URL = 'http://parlvid.mysociety.org/os/postcodes.csv';

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

    protected function execute(InputInterface $input, OutputInterface $output)
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

            // Assuming the CSV structure: [postcode, latitude, longitude]
            $postcode = new Postcode();
            $postcode->setPostcode($data[0]);
            $postcode->setLatitude(floatval($data[1]));
            $postcode->setLongitude(floatval($data[2]));

            // Persist the postcode entity
            $this->entityManager->persist($postcode);
        }

        // Flush all changes to the database
        $this->entityManager->flush();

        $output->writeln('Postcodes imported successfully.');
        return Command::SUCCESS;
    }
}
