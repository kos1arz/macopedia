<?php 

namespace App\Tests;

use App\Command\ImportCsvCommand;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCsvCommandTest extends KernelTestCase
{
    public function __construct(
        private ImportCsvCommand $importCsvCommand,
    ) {}

    public function testImportCsv()
    {
        $csvData = "name1;11111111\nname2;22222222\nname3;33333333";
        $tempCsvFile = tempnam(sys_get_temp_dir(), 'test_import_csv_');
        file_put_contents($tempCsvFile, $csvData);

        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $application = new Application($kernel);
        $application->add($this->importCsvCommand);

        $command = $application->find('import:csv');
        $commandTester = new CommandTester($command);

        $commandTester->execute(['file' => $tempCsvFile]);

        $this->assertSame(0, $commandTester->getStatusCode(), 'Import command failed.');

        $productRepository = $entityManager->getRepository(Product::class);

        $products = $productRepository->findAll();
        $this->assertCount(3, $products, 'Imported products count does not match.');

        unlink($tempCsvFile);
    }
}
