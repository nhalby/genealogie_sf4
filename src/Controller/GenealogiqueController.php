<?php

/*
 * Ceci sera ajouté dans tous vos fichiers PHP en entête.
 *
 * (c) Zozor <zozor@openclassrooms.com>
 *
 * A adapter et ré-utiliser selon vos besoins!
 */

namespace App\Controller;

use App\Entity\Parentalite;
use App\Entity\Personne;
use App\Form\Type\PersonneType;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GenealogiqueController extends AbstractController
{
    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();
        $services['App\Repository\PersonneRepository'] = PersonneRepository::class;
        $services['genealogie.arbregenealogique'] = ArbreGenealogique::class;

        return $services;
    }

    /**
     * @Route("/index", name="name")
     */
    public function index()
    {
        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personnes = $personneRepo->findAllPersonnes();
        $listePersonnes = [];

        return $this->render('genealogique/index.html.twig', [
            'controller_name' => 'GenealogiqueController',
            'listePersonnes' => $personnes,
            'action' => 'toto',
        ]);
    }

    /**
     * @Route("/selectionner_pour_modifier_personne", name="selectionnerModifierPersonne")
     */
    public function selectionnerModifierPersonne(Request $request)
    {
        $personne = new Personne();
        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personnes = $personneRepo->findAllPersonnes();

        $listePersonnes = [];
        foreach ($personnes as $oPersonne) {
            $listePersonnes[$oPersonne->__toString()] = $oPersonne->getId();
        }

        $form = $this->createFormBuilder()
            ->add('personne', ChoiceType::class, [
                'required' => false,
                'label_format' => 'Selectionnez votre mére : ',
                'choices' => $listePersonnes,
                ])
            ->add('Selectionner', SubmitType::class)
            ->add('action', HiddenType::class, [
                'action' => 'modifierPersonne',
            ])
            ->getForm();

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            
            return $this->redirectToRoute('modifierPersonne', ['idPersonne' => $request->request->get('form')['personne']]);
        }

        return $this->render('genealogique/selectionnerPersonne.html.twig', [
            'controller_name' => 'GenealogiqueController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier_personne/{idPersonne}", name="modifierPersonne", requirements={"idPersonne"="\d+"})
     */
    public function modifierPersonne(int $idPersonne, Request $request)
    {
        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personne = $personneRepo->findOneBy(['id' => $idPersonne, 'validee' => 1]);
        $personnes = $personneRepo->findAllPersonnes();
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(PersonneType::class, $personne, [
            'entity_manager' => $entityManager,
        ]);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            /*$data = $form->getData();
            $personne->setNom($data['nom']);
            $personne->setSurnom($data['surnom']);
            $personne->setPrenom($data['prenom']);
            $personne->setPrenom2($data['prenom2']);
            $personne->setPrenom3($data['prenom3']);
            $personne->setPrenom4($data['prenom4']);
            $personne->setSexe($data['sexe']);
            $personne->setTypeAscendence($data['typeAscendence']);*/
            $em->persist($personne);
            $em->flush();
            $parent1 = $personne->getParent1();
            $parent2 = $personne->getParent2();
            if ('' !== $parent1 && 0 !== $parent1 && isset($parent1)) {
                $parentalite1 = new Parentalite();
                $parentalite1->setIdParent($parent1);
                $parentalite1->setIdPersonne($personne->getId());
                $em->persist($parentalite1);
                $em->flush();
            }
            if ('' !== $parent2 && 0 !== $parent2 && isset($parent2)) {
                $parentalite2 = new Parentalite();
                $parentalite2->setIdParent($parent2);
                $parentalite2->setIdPersonne($personne->getId());
                $em->persist($parentalite2);
                $em->flush();
            }
        }

        return $this->render('genealogique/ajouterPersonne.html.twig', [
            'controller_name' => 'GenealogiqueController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajouter_personne", name="ajouterPersonne")
     */
    public function ajouterPersonne(Request $request)
    {
        // creates a task object and initializes some data for this example
        $personne = new Personne();
        $personne->setValidee(0);
        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personnes = $personneRepo->findAllPersonnes();
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(PersonneType::class, $personne, [
            'entity_manager' => $entityManager,
        ]);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            /*$data = $form->getData();
            $personne->setNom($data['nom']);
            $personne->setSurnom($data['surnom']);
            $personne->setPrenom($data['prenom']);
            $personne->setPrenom2($data['prenom2']);
            $personne->setPrenom3($data['prenom3']);
            $personne->setPrenom4($data['prenom4']);
            $personne->setSexe($data['sexe']);
            $personne->setTypeAscendence($data['typeAscendence']);*/
            $em->persist($personne);
            $em->flush();
            $parentalite = new Parentalite();
            $parent1 = $personne->getParent1();
            $parent2 = $personne->getParent2();
            if (isset($parent1) && 0 !== $parent1) {
                $parentalite1 = new Parentalite();
                $parentalite1->setIdParent($parent1);
                $parentalite1->setIdPersonne($personne->getId());
                $em->persist($parentalite1);
                $em->flush();
            }
            if (isset($parent1) && 0 !== $parent2) {
                $parentalite2 = new Parentalite();
                $parentalite2->setIdParent($parent2);
                $parentalite2->setIdPersonne($personne->getId());
                $em->persist($parentalite2);
                $em->flush();
            }
        }

        return $this->render('genealogique/ajouterPersonne.html.twig', [
            'controller_name' => 'GenealogiqueController',
            'form' => $form->createView(),
            'action' => 'toto',
        ]);
    }

    /**
     * @Route("/selectionner_personne_arbre_genealogique", name="selectionnerPersonneArbreGenealogique")
     */
    public function selectionnerPersonneArbreGenealogique(Request $request)
    {
        $personne = new Personne();
        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personnes = $personneRepo->findAllPersonnes();

        $listePersonnes = [];
        foreach ($personnes as $oPersonne) {
            $listePersonnes[$oPersonne->__toString()] = $oPersonne->getId();
        }

        $form = $this->createFormBuilder()
            ->add('personne', ChoiceType::class, [
                'required' => false,
                'label_format' => 'Selectionnez votre mére : ',
                'choices' => $listePersonnes,
                ])
            ->add('Selectionner', SubmitType::class)
            ->add('action', HiddenType::class, [
                'action' => 'modifierPersonne',
            ])
            ->getForm();

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $personne = $personneRepo->findOneById($request->request->get('form')['personne']);
            $this->container->get('session')->set('personne', $personne);

            return $this->redirectToRoute('arbreGenealogique');
        }

        return $this->render('genealogique/selectionnerPersonne.html.twig', [
            'controller_name' => 'GenealogiqueController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/arbre_genealogique", name="arbreGenealogique")
     */
    public function arbreGenealogique(Request $request)
    {
        $arbre = $this->container->get('genealogie.arbregenealogique')->findArbreDescendenceNiveau1($this->container->get('session')->get('personne'));

        $affichageArbre = $this->container->get('genealogie.arbregenealogique')->generateArbreGenealogique($arbre);

        return $this->render('genealogique/arbreGenealogique.html.twig', [
            'arbre' => $affichageArbre,
        ]);
    }
}
