<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Gallery;

class GalleryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $gallery = new Gallery;
        $gallery->setCaption('Kitten')
        	->setUrl('http://placekitten.com/600/300')
        	->setEventDate(new DateTime)
        ;
        $gallery1 = new Gallery;
        $gallery1->setCaption('Kitten')
        	->setUrl('http://placekitten.com/400/300')
        	->setEventDate(new DateTime)
        ;

        $manager->persist($gallery);
        $manager->persist($gallery1);
        $manager->flush();
    }
}
