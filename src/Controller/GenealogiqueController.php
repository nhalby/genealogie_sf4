<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PersonneRepository;
use App\Entity\Personne;
use App\Form\Type\PersonneType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class GenealogiqueController extends AbstractController
{
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
        }

        return $this->render('genealogique/ajouterPersonne.html.twig', [
            'controller_name' => 'GenealogiqueController',
            'form' => $form->createView(),
            'action' => 'toto'
        ]);
    }
}
