<?php

namespace App\Handler;

use App\Entity\Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HandlerChain
{
    private $httpClient;
    private $chain;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->initChain();
    }

    private function initChain()
    {
        $chain = new ConsentScoreHandler(null);
        $chain = new EducationScoreHandler($chain);
        $chain = new EmailScoreHandler($chain);
        $chain = new PhoneScoreHandler($chain, $this->httpClient);
        $this->chain = $chain;
    }

    public function run(Client $client): int
    {
        return $this->chain->handle($client, 0);
    }
}