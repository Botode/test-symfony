<?php

namespace App\Handler;

use App\Entity\Client;

interface HandlerInterface
{
    public function handle(Client $client, int $score): int;
}
