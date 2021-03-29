<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Maison;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // $maison = new Maison();
        // $maison->setTitle('Jolie maison de campagne');
        // $maison->setDescription('Maison de campagne en borudre de rivière avec domaine de 2 hectares attenant');
        // $maison->setSurface(185);
        // $maison->setRooms(12);
        // $maison->setBedrooms(6);
        // $maison->setPrice(580000);
        // $maison->setImg1('maison1-1.png');
        // $manager->persist($maison);

        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $maison = new Maison();
            $maison->setTitle('Maison de ' . $faker->name());
            $maison->setDescription($faker->text(255));
            $maison->setSurface($faker->numberBetween(59, 199));
            $maison->setRooms($faker->numberBetween(5, 15));
            $maison->setBedrooms($faker->numberBetween(1, 4));
            $maison->setPrice($faker->numberBetween(75000, 580000));
            $maison->setImg1('maison1-1.jpg');
            $manager->persist($maison);
        }

        $manager->flush();
    }
}
