<?php 
declare(strict_types=1);

namespace App\Service;

use App\AppInterface\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LoggerService implements LoggerInterface
{
    const LOG_PATH= 'logPath';

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function log(string $message): void
    {
        file_put_contents(
            $this->params->get(self::LOG_PATH), 
            date('Y-m-d H:i:s') . ' | ' . $message . PHP_EOL, FILE_APPEND
        );
    }
}
