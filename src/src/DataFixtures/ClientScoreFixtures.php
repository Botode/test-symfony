<?php

namespace App\DataFixtures;

use App\Entity\ClientScore;
use App\Service\ScoringService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ClientScoreFixtures extends Fixture implements DependentFixtureInterface
{
    private ScoringService $scoringService;

    public function __construct(ScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $client = $this->getReference('client_'.$i);
            $score = $this->scoringService->calcClientScore($client);

            $clientScore = new ClientScore();
            $clientScore->setClient($client);
            $clientScore->setScore($score);

            $manager->persist($clientScore);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixtures::class,
        ];
    }
}
