<?php

namespace App\Handler;

use App\Enum\ConsentType;
use App\Entity\Client;

class ConsentScoreHandler extends BasicHandler
{
    public function handle(Client $client, int $score): int
    {
        $consent = intval($client->getConsent());
        $consentType = $this->getConsentType($consent);
        $score += $consentType->score();

        return parent::handle($client, $score);
    }

    private function getConsentType(bool $consent): ConsentType {
        return ConsentType::from($consent);
    }
}