<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public const USERNAMES = [
        'john.doe',
        'ivan.iglesias'
    ];

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::USERNAMES as $username) {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($this->encoder->encodePassword($user, 'secret'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
