<?php
declare(strict_types=1);

namespace App\Service;

use App\AppInterface\FileUploadInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CsvFile implements FileUploadInterface
{
    const CSV_DIRECTORY = 'csv_directory';

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function upload(UploadedFile $file): void
    {
        $file->move(
            $this->params->get(self::CSV_DIRECTORY),
            uniqid() . '.' . $file->getClientOriginalExtension()
        );
    }
}
