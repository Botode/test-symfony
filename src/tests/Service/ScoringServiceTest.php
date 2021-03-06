<?php

namespace App\Tests\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Service\ScoringService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ScoringServiceTest extends KernelTestCase
{

    protected Client $client;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $clientRepository = $container->get(ClientRepository::class);
        $this->client = $clientRepository->findOneBy(['email' => 'user30@yahoo.ru']);
    }

    public function testCalcClientScoreOnline()
    {
        $mockResponse = new MockResponse('{"oper":{"brand":""}}', ['http_code' => 200]);
        $service = $this->getServiceWithResponse($mockResponse);
        $score = $service->calcClientScore($this->client);
        $this->assertEquals(23, $score);
    }

    public function testCalcClientScoreOffline()
    {
        $service = $this->getService();
        $score = $service->calcClientScore($this->client);
        $this->assertEquals(21, $score);
    }

    private function getService(): ScoringService
    {
        $mockResponse = new MockResponse(null, ['http_code' => 404]);
        return $this->getServiceWithResponse($mockResponse);
    }

    private function getServiceWithResponse($mockResponse): ScoringService
    {
        $httpClient = new MockHttpClient($mockResponse);
        return new ScoringService($httpClient);
    }
}
