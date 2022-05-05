<?php

namespace App\Handler;

use App\Enum\PhoneType;
use App\Entity\Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Exception;

class PhoneScoreHandler extends BasicHandler
{
    private $httpClient;

    public function __construct(?HandlerInterface $handler, HttpClientInterface $httpClient)
    {
        parent::__construct($handler);
        $this->httpClient = $httpClient;
    }

    public function handle(Client $client, int $score): int
    {
        $phoneType = $this->getPhoneType($client->getPhone());
        $score += parent::handle($client, $phoneType->score());
        return $score;
    }

    private function getPhoneType(string $phone): PhoneType
    {
        try {
            return $this->getPhoneTypeOnline($phone);
        } catch (Exception $e) {
            return $this->getPhoneTypeOffline($phone);
        }
    }

    private function getPhoneTypeOnline(string $phone): PhoneType
    {
        $response = $this->httpClient->request(
            'GET',
            'http://htmlweb.ru/api/mnp/phone/' . $phone,
        );

        if (200 !== $response->getStatusCode()) {
            throw new Exception('Invalid response');
        }
        $responseJson = $response->getContent();
        $responseData = json_decode($responseJson, true, 512, JSON_THROW_ON_ERROR);

        $brand = $responseData['oper']['brand'];

        return PhoneType::fromOper($brand);
    }

    private function getPhoneTypeOffline(string $phone): PhoneType
    {
        $prefix = intval(substr($phone, 1, 3));
        return PhoneType::fromPrefix($prefix);
    }
}
