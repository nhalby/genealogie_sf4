<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PersonneRepository;
use App\Entity\Personne;
use App\Entity\Parentalite;
use App\Form\Type\PersonneType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class GenealogiqueController extends AbstractController
{

    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();
        $services['App\Repository\PersonneRepository'] = PersonneRepository::class;
        $services['genealogie.arbregenealogique'] = ArbreGenealogique::class;

        return $services;
    }

    private $arbreGenealogique;

    public function GenealogiqueController(ArbreGenealogique $arbreGenealogique)
    {
        $this->arbreGenealogique = $arbreGenealogique;
    }

    /**
     * @Route("/index", name="name")
     */
    public function index()
    {
        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personnes = $personneRepo->findAllPersonnes();
        $listePersonnes = array();
        /*foreach ($personnes as $personne) {
            array_merge($listePersonnes, array($personne));
        }
        $form = $this->createForm(SelectionPersonneAccueilType::class, null, $listePersonnes);*/
        /*$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $personnes);
        
        $formBuilder
            ->add('personne', ChoiceType::class, 
                ['choices' => [$listePersonnes]]);*/

        return $this->render('genealogique/index.html.twig', [
            'controller_name' => 'GenealogiqueController',
            'listePersonnes' => $personnes,
            'action' => 'toto'
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

        $listePersonnes = array();
        foreach ($personnes as $oPersonne) {
            $listePersonnes[$oPersonne->__toString()] = $oPersonne->getId();
        }

        $form = $this->createFormBuilder()
            ->add('personne', ChoiceType::class, [
                'required'   => false,
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
            //$personne = $personneRepo->findPersonneByIdPersonne($request->request->get('form')['personne']);
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
    public function modifierPersonne($idPersonne, Request $request)
    {
        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personne = $personneRepo->findOneBy(array('id' => $idPersonne, 'validee' => 1));
        $personnes = $personneRepo->findAllPersonnes();

        $form = $this->createForm(PersonneType::class, $personne, array(
            'personnes' => $personnes
        ));

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($personne);
            $em->flush();
            $parent1 = $personne->getParent1();
            $parent2 = $personne->getParent2();
            if ($parent1 != "" && $parent1 != 0) {
				$parentalite1 = new Parentalite();
				$parentalite1->setIdParent($parent1);
				$parentalite1->setIdPersonne($personne->getId());
                $em->persist($parentalite1);
                $em->flush();
			}
			if ($parent2 != "" && $parent2 != 0) {
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
            'action' => 'toto'
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

        $form = $this->createForm(PersonneType::class, $personne, array(
            'personnes' => $personnes
        ));

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($personne);
            $em->flush();
            $parentalite = new Parentalite();
            $parent1 = $personne->getParent1();
            $parent2 = $personne->getParent2();
            if ($parent1 != "" && $parent1 != 0) {
				$parentalite1 = new Parentalite();
				$parentalite1->setIdParent($parent1);
				$parentalite1->setIdPersonne($personne->getId());
                $em->persist($parentalite1);
                $em->flush();
			}
			if ($parent2 != "" && $parent2 != 0) {
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
            'action' => 'toto'
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

        $listePersonnes = array();
        foreach ($personnes as $oPersonne) {
            $listePersonnes[$oPersonne->__toString()] = $oPersonne->getId();
        }

        $form = $this->createFormBuilder()
            ->add('personne', ChoiceType::class, [
                'required'   => false,
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
