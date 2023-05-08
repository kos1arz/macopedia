<?php 
declare(strict_types=1);

namespace App\AppInterface;

interface LoggerInterface 
{
    public function log(string $message): void;
}
