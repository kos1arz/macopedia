<?php 

namespace App\AppInterface;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploadInterface 
{
    public function upload(UploadedFile $file): void;
}
