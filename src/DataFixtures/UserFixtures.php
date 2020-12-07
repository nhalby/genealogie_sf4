<?php

/*
 * Ceci sera ajouté dans tous vos fichiers PHP en entête.
 *
 * (c) Zozor <zozor@openclassrooms.com>
 *
 * A adapter et ré-utiliser selon vos besoins!
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
	private $encode;
 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encode = $encoder;
    }
	
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
		
		$userAdmin = new User();
        $userAdmin->setNomComplet('admin');

        $userAdmin->setEmail('admin@admin.lan');
        $hash = $this->encode->encodePassword($userAdmin, 'admin');
        $userAdmin->setPassword($hash);
		$userAdmin->setRoles(array('ROLE_SUPER_ADMIN'));
        $manager->persist($userAdmin);

        $manager->flush();
    }
}
