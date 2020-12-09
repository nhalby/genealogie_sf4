<?php

/*
 * Ceci sera ajouté dans tous vos fichiers PHP en entête.
 *
 * (c) Zozor <zozor@openclassrooms.com>
 *
 * A adapter et ré-utiliser selon vos besoins!
 */

namespace App\Controller;

use App\Repository\PersonneRepository;

class ArbreGenealogique
{
    private $manager;

    public function __construct(PersonneRepository $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Méthode qui génére l'arbre genealogique.
     *
     * @return bool
     */
    public function generateArbreGenealogique($data): string
    {
        $affichageArbre = '';
        if (\is_array($data)) {                
            if (\array_key_exists('parent', $data)) {
                $affichageArbre .= '<li><a href="#" class="'.$data['parent']->getTypeAscendence().'">'.$data['parent'].'<span></span></a>';
                switch(true) {
                    case (isset($data['enfant']) && \is_array($data['enfant']) && \count($data['enfant']) > 0):
                        $affichageArbre .= '<ul>';
                        foreach ($data['enfant'] as $enfant) {
                            if (\is_array($enfant)) {
                                $affichageArbre .= $this->generateArbreGenealogique($enfant);
                            } else {
                                $affichageArbre .= $this->generateArbreGenealogique($enfant);
                            }
                        }
                        $affichageArbre .= '</ul>';
                    break;
                    case (isset($data['enfant']) && \is_array($data['enfant'])) :
                        $affichageArbre .= '<ul>';
                        $affichageArbre .= '<li>
                                                    <a href="#" class="'.$data['enfant']->getTypeAscendence().'">'.$data['enfant'].'<span></span></a></li>';
                        $affichageArbre .= '</ul>';
                    break;
                    default :
                        $affichageArbre .= '<li>
                                                    <a href="#" class="'.$data->getTypeAscendence().'">'.'<span></span></a></li>';
                }
                $affichageArbre .= '</li>';
            } else {
                $affichageArbre .= '<li><a href="#" class="'.$data->getTypeAscendence().'">'.'<span></span></a></li>';
            }
        } else {
            $affichageArbre .= '<li><a href="#" class="'.$data->getTypeAscendence().'">'.$data.'<span></span></a></li>';
        }

        return $affichageArbre;
    }

    public function findArbreDescendenceNiveau1(?Personne $personne): ?array
    {
        $aArbrePersonneNiveau1 = [];
        $aArbre = [];
        $aArbreEnfantNiveau1 = [];
        $enfants = $this->manager->findEnfantParent($personne->getId());
        $aEnfants = [];
        if (isset($enfants) && \count($enfants) > 0) {
            foreach ($enfants as $enfant) {
                if (isset($enfant)) {
                    array_push($aArbreEnfantNiveau1, $this->findArbreDescendenceNiveau1($enfant));
                }
                $aArbrePersonneNiveau1 = $aArbreEnfantNiveau1;
                $aArbrePersonneNiveau1 = ['parent' => $personne, 'enfant' => $aArbrePersonneNiveau1];
            }
        } else {
            $aArbrePersonneNiveau1 = $personne;
        }

        return $aArbrePersonneNiveau1;
    }
}
