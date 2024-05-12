<?php

namespace App\DataFixtures;

use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {

    }

    public function load(ObjectManager $manager)
    {
        $servicesData = [
            ['name' => 'Car valuation', 'price' => '500.00'],
            ['name' => 'Apartment valuation', 'price' => '1000.00'],
            ['name' => 'Business valuation', 'price' => '1500.00'],
        ];

        foreach ($servicesData as $data) {
            $service = new Service();
            $service->setName($data['name']);
            $service->setPrice($data['price']);
            $manager->persist($service);
        }

        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                111111
            )
        );

        $manager->persist($user);

        $manager->flush();
    }
}
