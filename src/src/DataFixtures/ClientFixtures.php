<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Enum\EducationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $domains = ['gmail', 'mail', 'yahoo', 'yandex'];
        $educations = EducationType::cases();

        for ($i = 1; $i <= 50; $i++) {
            $client = new Client();
            $client->setFirstname('Name'.$i);
            $client->setLastname('Surname'.$i);
            $client->setPhone(sprintf('79%02d%07d', ($i * 17) % 100, mt_rand(0, 9999999)));
            $client->setEmail('user'.$i.'@'.$domains[$i % count($domains)].'.ru');
            $client->setEducation($educations[$i % count($educations)]);
            $client->setConsent(boolval($i % 2));
            $manager->persist($client);

            $this->addReference('client_'.$i, $client);
        }

        $manager->flush();

    }
}
