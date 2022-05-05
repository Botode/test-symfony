<?php
namespace App\Service;

use App\Entity\Client;
use App\Handler\HandlerChain;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScoringService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function calcClientScore(Client $client): int
    {
        $chain = new HandlerChain($this->httpClient);
        $score = $chain->run($client);
        return $score;
    }
}