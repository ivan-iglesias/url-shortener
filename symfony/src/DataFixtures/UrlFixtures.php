<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Url;

class UrlFixtures extends Fixture implements DependentFixtureInterface
{
    public const URLS = [
        ['www.elcorreo.com/', 'vfcjm1852'],
        ['www.20minutos.es/', '2019122902'],
        ['www.deia.eus/', '2019122903'],
        ['www.eldiario.es/', '2019122904']
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::URLS as $item) {
            $url = new Url();
            $url->setNamelong($item[0]);
            $url->setNameshort($item[1]);
            $manager->persist($url);

            $this->addReference($item[0], $url);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
