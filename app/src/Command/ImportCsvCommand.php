<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Product;
use App\Service\LoggerService;
use App\Factory\ProductFactory;
use App\Repository\ProductRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

class ImportCsvCommand extends Command
{
    protected static $defaultName = 'import:csv';
    private ProductFactory $productFactory;
    private ProductRepository $productRepository;
    private LoggerService $logger;
    private string $csvDir;

    public function __construct(
        string $csvDir,
        LoggerService $logger,
        ProductFactory $productFactory,  
        ProductRepository $productRepository
    ) {
        parent::__construct();

        $this->csvDir = $csvDir;
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
    }

    protected function configure(): void
    {
        $this->setDescription('Start import csv files')
            ->setHelp('This command allows you to import CSV files with product data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $output->writeln('Start CSV Import');

        $finder = new Finder();
        $finder->files()->in($this->csvDir)->name('*.csv');

        if (!$finder->hasResults()) {
            $output->writeln('<error>No CSV files found in the specified directory.</error>');
            
            return Command::FAILURE;
        }

        $progressBar = new ProgressBar($output);
        $progressBar->start();

        foreach ($finder as $file) {
            $handle = fopen($file->getRealPath(), 'r');
            if ($handle !== false) {
                while ((($data = fgetcsv($handle, 1000, ';')) && count($data) === 2)) {
                    $name = $data[0];
                    $productIndex = intval($data[1]);

                    if ($this->productRepository->findOneBy([Product::FIELD_INDEX => $productIndex])) {
                        $this->logger->log("Duplicate product: {$name};{$productIndex}");
                    } else {
                        $this->productRepository->save(
                            $this->productFactory->createProduct($name, $productIndex)
                        );
                    }
                    $progressBar->advance();
                }

                fclose($handle);
                unlink($file->getRealPath());
            }
        }

        $progressBar->finish();   
        $io->success('All files have been loaded');

        return Command::SUCCESS;
    }
}
