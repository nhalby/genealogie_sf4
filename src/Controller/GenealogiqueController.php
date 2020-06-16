<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PersonneRepository;
use App\Entity\Personne;
use App\Form\Type\SelectionPersonneAccueilType;

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
}
