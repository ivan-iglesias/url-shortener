<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Activity;
use Carbon\Carbon;

class ActivityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $devices = ['smartphone', 'computer',];

        foreach (UrlFixtures::URLS as $url) {
            for ($i=0; $i < 5; $i++) {

                $randomDateTime = Carbon::now()
                    ->subDays(rand(0, 3))
                    ->subMinutes(rand(0, 1440));

                $activity = new Activity();
                $activity->setCreatedAt($randomDateTime);
                $activity->setDevice($devices[array_rand($devices, 1)]);
                $activity->setUrl($this->getReference($url[0]));
                $manager->persist($activity);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UrlFixtures::class,
        );
    }
}
