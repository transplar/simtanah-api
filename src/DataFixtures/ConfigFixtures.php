<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Config;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $welcome = new Config;
        $welcome->setName('welcome')
        	->setContent('')
        ;

        $profile = new Config;
        $profile->setName('profile')
        	->setContent('')
        ;

        $contact = new Config;
        $contact->setName('contact')
        	->setContent('')
        ;

        $aduan = new Config;
        $aduan->setName('aduan')
        	->setContent('')
        ;

        $manager->persist($welcome);
        $manager->persist($profile);
        $manager->persist($contact);
        $manager->persist($aduan);
        $manager->flush();
    }
}
