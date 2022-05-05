<?php

namespace App\Handler;

use App\Entity\Client;

class EducationScoreHandler extends BasicHandler
{
    public function handle(Client $client, int $score): int
    {
        $educationType = $client->getEducation();
        $score +=  $educationType->score();
        return $score;
    }
}
