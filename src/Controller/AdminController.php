<?php

/*
 * Ceci sera ajouté dans tous vos fichiers PHP en entête.
 *
 * (c) Zozor <zozor@openclassrooms.com>
 *
 * A adapter et ré-utiliser selon vos besoins!
 */

 namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Form\Type\AValiderType;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AdminController extends AbstractController
{

    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function getCredentials(Request $request)
    {
        return $credentials = [
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
    }

    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();
        $services['App\Repository\PersonneRepository'] = PersonneRepository::class;
        $services['genealogie.arbregenealogique'] = ArbreGenealogique::class;
        $services['genealogie.genealogiquecontroller'] = GenealogiqueController::class;

        return $services;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $personne = new Personne();
        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personnes = $personneRepo->findAllPersonnes();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'action'          => 'effacerPersonne',
        ]);
    }

    /**
     * @Route("/selectionner_personne_a_effacer", name="selectionner.personne.a.effacer")
     */
    public function selectionnerPersonneAEffacer()
    {
        $personne = new Personne();
        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personnes = $personneRepo->findAllPersonnes();

        return $this->render('admin/selectionnerPersonneAEffacer.html.twig', [
            'controller_name' => 'AdminController',
            'listePersonne'   => $personnes,
            'action'          => 'effacer_personne',
        ]);
    }

    /**
     * @Route("/effacer_personne", name="delete.personne")
     */
    public function effacerPersonne(Request $request)
    {
        
        $submittedToken = $request->request->get('_csrf_token');

        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personnes = $personneRepo->findAllPersonnes();
        $messageInfo = null;
        $personne = $personneRepo->findOneById($request->request->get('personne'));
        $sPersonne = (isset($personne))?$personne->__toString():null;

        if ($this->isCsrfTokenValid('authenticate', $submittedToken)) {   
            $em = $this->getDoctrine()->getManager();
            $em->remove($personne);
            $em->flush();
            $messageInfo = "Suppression de la personne : ".$sPersonne;
        } else if (isset($sPersonne)) {
            $messageInfo = "Impossible de supprimer la personne : ".$sPersonne;
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'listePersonne'   => $personnes,
            'message_info'    => $messageInfo,
        ]);
    }

    /**
     * @Route("/selectionner_personne_a_valider", name="selectionner.personne.a.valider")
     */
    public function selectionnerPersonneAValider(Request $request)
    {

        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
        $personnes = $personneRepo->findBy(array('validee' => 0));
        $messageInfo = null;
        $choices = array();
        foreach($personnes as $personne) {
            $choices[$personne->__toString()] = $personne->getId();
        }
        $form = $this->createFormBuilder()
            ->add('personne', ChoiceType::class, [
                'required' => false,
                'label_format' => 'Selectionnez les personnes à valider : ',
                'multiple'=>true, 
                'expanded'=>true, 
                'choices' => $choices,
                ])
            ->add('Selectionner', SubmitType::class)
            ->setAction($this->generateUrl('valider.personne'))
            ->getForm();

        //$form->handleRequest($request);

        return $this->render('admin/validerPersonne.html.twig', [
            'controller_name' => 'AdminController',
            'form'            => $form->createView(),
        ]);
    }

    /**
     * @Route("/valider_personne", name="valider.personne")
     */
    public function validerPersonne(Request $request)
    {
        $submittedData = $request->request->get('form');
        $messageInfo = null;

        if ($this->isCsrfTokenValid('authenticate', $request->request->get('_csrf_token'))) {   
            $em = $this->getDoctrine()->getManager();
            foreach ($submittedData['personne'] as $personneId) {
                $personneRepo = $this->getDoctrine()->getRepository(Personne::class);
                $personne = $personneRepo->findOneById($personneId);
                $personne->setValidee(1);
                $em->persist($personne);
                $messageInfo .= 'Validation de ' . $personne->__toString() . '. ';
            }
            $em->flush();
            
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminController',
                'message_info'    => $messageInfo,
            ]);
        }


        return $this->render('admin/selectionnerPersonneAEffacer.html.twig', [
            'controller_name' => 'AdminController',
            'message_info'    => $messageInfo,
        ]);
    }
}
