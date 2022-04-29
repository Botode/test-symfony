<?php
namespace App\Service;

use App\Config\ConsentType;
use App\Config\EmailType;
use App\Config\PhoneType;
use App\Entity\Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Exception;

class ScoringService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function calcClientScore(Client $client): int
    {
        $score = 0;
        $score += $this->calcPhoneScore($client);
        $score += $this->calcEmailScore($client);
        $score += $this->calcEducationScore($client);
        $score += $this->calcConsentScore($client);
        return $score;
    }

    public function calcPhoneScore(Client $client): int
    {
        $phoneType = $this->getPhoneType($client->getPhone());
        return $phoneType->score();
    }

    public function calcEmailScore(Client $client): int
    {
        $emailType = $this->getEmailType($client->getEmail());
        return $emailType->score();
    }

    public function calcEducationScore(Client $client): int
    {
        $educationType = $client->getEducation();
        return $educationType->score();
    }

    public function calcConsentScore(Client $client): int
    {
        $consent = intval($client->getConsent());
        $consentType = $this->getConsentType($consent);
        return $consentType->score();
    }

    private function getPhoneType(string $phone): PhoneType {
        try {
            return $this->getPhoneTypeOnline($phone);
        } catch (Exception $e) {
            return $this->getPhoneTypeOffline($phone);
        }
    }

    private function getEmailType(string $email): EmailType {
        $domain = preg_match('/.+@(.+)\..+/', $email, $out) ? $out[1] : '';
        return EmailType::fromDomain($domain);
    }

    private function getConsentType(bool $consent): ConsentType {
        return ConsentType::from($consent);
    }

    private function getPhoneTypeOnline(string $phone): PhoneType {
        $response = $this->httpClient->request(
            'GET',
            'http://htmlweb.ru/api/mnp/phone/'.$phone,
        );

        if (200 !== $response->getStatusCode()) {
            throw new Exception('Invalid response');
        }
        $responseJson = $response->getContent();
        $responseData = json_decode($responseJson, true, 512, JSON_THROW_ON_ERROR);

        $brand = $responseData['oper']['brand'];

        return PhoneType::fromOper($brand);
    }

    private function getPhoneTypeOffline(string $phone): PhoneType {
        $prefix = intval(substr($phone, 1, 3));
        return PhoneType::fromPrefix($prefix);
    }

}