<?php

// src/GenealogieBundle/ArbreGenealogique/ArbreGenealogique.php

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
    public function generateArbreGenealogique($data)
    {
        $affichageArbre = '';
        if(is_array($data)){
            if (array_key_exists('parent',$data)) {
                    $affichageArbre .= '<li><a href="#" class="'.$data['parent']->getTypeAscendence().'">'.$data['parent'].'<span></span></a>';
                if (isset($data['enfant']) && is_array($data['enfant']) && sizeof($data['enfant']) > 0) {
                    $affichageArbre .= '<ul>';
                    foreach($data['enfant'] as $enfant) {
                        if(is_array($enfant)){
                            $affichageArbre .= $this->generateArbreGenealogique($enfant);
                        } else {
                            $affichageArbre .= $this->generateArbreGenealogique($enfant);
                        }
                    }
                    $affichageArbre .= '</ul>';
                } elseif (isset($data['enfant']) && is_array($data['enfant'])) {
                    $affichageArbre .= '<ul>';
                    $affichageArbre .= '<li>
                                                <a href="#" class="'.$data['enfant']->getTypeAscendence().'">'.$data['enfant'].'<span></span></a></li>';
                    $affichageArbre .= '</ul>';
                } else {
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

    public function findArbreDescendenceNiveau1($personne)
    {
        $aArbrePersonneNiveau1 = [];
        $aArbre = [];
        $aArbreEnfantNiveau1 = [];
        $enfants = $this->manager->findEnfantParent($personne->getId());
        $aEnfants = [];
        if (isset($enfants) && sizeof($enfants) > 0) {
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
