<?php


namespace App\Logging;

use Monolog\Formatter\JsonFormatter as Json;

class JsonFormatter
{
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new Json());
        }
    }
}