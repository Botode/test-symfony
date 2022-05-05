<?php

namespace App\Handler;

use App\Enum\EmailType;
use App\Entity\Client;

class EmailScoreHandler extends BasicHandler
{
    public function handle(Client $client, int $score): int
    {
        $emailType = $this->getEmailType($client->getEmail());
        $score += parent::handle($client, $emailType->score());
        return $score;
    }

    private function getEmailType(string $email): EmailType {
        $domain = preg_match('/.+@(.+)\..+/', $email, $out) ? $out[1] : '';
        return EmailType::fromDomain($domain);
    }
}