<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\SecurityController;
use App\Repository\PersonneRepository;
use App\Entity\Personne;

class AdminController extends AbstractController
{

    public static function getSubscribedServices(): array
{
    $services = parent::getSubscribedServices();
    $services['App\Repository\PersonneRepository'] = PersonneRepository::class;
    $services['genealogie.arbregenealogique'] = ArbreGenealogique::class;

    return $services;
}
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {

        $this->container->get('genealogie.arbregenealogique');

        $personneRepository = $this->getDoctrine()->getRepository(Personne::class);
        $listePersonne = $personneRepository->findAllPersonnes();

        var_dump($listePersonne);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
