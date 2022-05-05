<?php

namespace App\Handler;

use App\Entity\Client;
use App\Handler\HandlerInterface;

abstract class BasicHandler implements HandlerInterface
{
    private $successor;

    public function __construct(?HandlerInterface $handler)
    {
        $this->successor = $handler;
    }

    public function handle(Client $client, int $score): int
    {
        if (is_null($this->successor)) {
            return $score;
        }
        return $this->successor->handle($client, $score);
    }
}