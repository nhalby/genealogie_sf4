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
use App\Entity\Personne;

class PersonnesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $mere = new Personne();
         $mere->setNom('TOTO');
         $mere->setPrenom('Isabelle');
         $mere->setPrenom2('Marie');
         $mere->setSurnom('TotIsa');
         $mere->setSexe('F');
         $mere->setTypeAscendence('M');
         $mere->setValidee('1');

         $manager->persist($mere);

         $pere = new Personne();
         $pere->setNom('TUTU');
         $pere->setPrenom('Mickael');
         $pere->setPrenom2('Jean');
         $pere->setSurnom('Tu');
         $pere->setSexe('M');
         $pere->setTypeAscendence('M');
         $pere->setValidee('1');

         $manager->persist($pere);

         $personne = new Personne();
         $personne->setNom('SOLO');
         $personne->setPrenom('Han');
         $personne->setSurnom('OBI');
         $personne->setSexe('M');
         $personne->setTypeAscendence('M');
         $personne->setParent1($mere->getid());
         $personne->setParent2($pere->getid());
         $personne->setValidee('1');

         $manager->persist($personne);

         $personne2 = new Personne();
         $personne2->setNom('TITI');
         $personne2->setPrenom('Mickael');
         $personne2->setPrenom2('Paul');
         $personne2->setPrenom3('Simon');
         $personne2->setPrenom3('Nicolas');
         $personne2->setSurnom('Tim');
         $personne2->setSexe('M');
         $personne2->setTypeAscendence('M');
         $personne2->setParent1($mere->getid());
         $personne2->setParent2($pere->getid());
         $personne2->setValidee('1');

         $manager->persist($personne2);

        $manager->flush();
    }
}