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

class AdminController extends AbstractController
{
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
            'listePersonne'   => $personnes,
            'action'          => 'effacerPersonne',
        ]);
    }

    /**
     * @Route("valider_personne", name="valider.personne")
     */
    public function validerPersonne()
    {
        $personnes = array();
        return $this->render('validerPersonnes.html.twig', [
            'controller_name' => 'AdminController',
            'listePersonne'   => $personnes,
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
        
        $submittedToken = $request->request->get('token');


        // 'delete-item' is the same value used in the template to generate the token
        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            // ... do something, like deleting an object
        }

        $personneRepo = $this->getDoctrine()->getRepository(Personne::class);   
        $em = $this->getDoctrine()->getManager();
        $personne = $personneRepo->findOneById($request->request->get('personne'));
        $em->remove($personne);
        $em->flush();
        $personnes = $personneRepo->findAllPersonnes();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'listePersonne'   => $personnes,
            'action'          => 'effacerPersonne',
        ]);
    }
}
