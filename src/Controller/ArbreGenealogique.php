<?php
// src/GenealogieBundle/ArbreGenealogique/ArbreGenealogique.php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PersonneRepository;

class ArbreGenealogique
{

    private $manager;

    public function __construct(PersonneRepository $manager)
    {
        $this->manager = $manager;
    }

   /**
   * Méthode qui génére l'arbre genealogique
   *
   * @param int $idUtilisateur
   * @return bool
   */
    public function generateArbreGenealogique(int $idUtilisateur)
    {
    $affichageArbre = '';
    /*if(is_array($data)){
        if (array_key_exists('parent',$data)) {
                $affichageArbre .= '<li><a href="#" class="'.$data['parent']->getClassTypeAscendence().'">'.$data['parent']->toStringListe().'<span>'.ArbreController::afficherInfosCompletePersonne($data['parent'],$con).'</span></a>';
            if (isset($data['enfant']) && is_array($data['enfant']) && sizeof($data['enfant']) > 0) {
                $affichageArbre .= '<ul>';
                //var_dump($data['enfant']);
                foreach($data['enfant'] as $enfant) {
                    if(is_array($enfant)){
                        $affichageArbre .= ArbreController::generateArbreGenealogique($enfant);
                    } else {
                        $affichageArbre .= ArbreController::generateArbreGenealogique($enfant);
                    }
                }
                $affichageArbre .= '</ul>';
            } elseif (isset($data['enfant']) && is_array($data['enfant'])) {
                $affichageArbre .= '<ul>';
                $affichageArbre .= '<li>
                                            <a href="#" class="'.$data['enfant']->getClassTypeAscendence().'">'.$data['enfant']->toStringListe().'<span>'.ArbreController::afficherInfosCompletePersonne($data['enfant'],$con).'</span></a></li>';
                $affichageArbre .= '</ul>';
            } else {
                $affichageArbre .= '<li>
                                            <a href="#" class="'.$data->getClassTypeAscendence().'">'.$data->toStringListe().'<span>'.ArbreController::afficherInfosCompletePersonne($data,$con).'</span></a></li>';
            }
            $affichageArbre .= '</li>';
        } else {
            $affichageArbre .= '<li><a href="#" class="'.$data->getClassTypeAscendence().'">'.$data->toStringListe().'<span>'.ArbreController::afficherInfosCompletePersonne($data,$con).'</span></a></li>';
        }
    } else {
            $affichageArbre .= '<li><a href="#" class="'.$data->getClassTypeAscendence().'">'.$data->toStringListe().'<span>'.ArbreController::afficherInfosCompletePersonne($data,$con).'</span></a></li>';
    }*/
    return $affichageArbre;
    }


    public static function findArbreDescendenceNiveau1($personne) {  
    $aArbrePersonneNiveau1 = array();
    $personne = PersonneModel::findPersonneByIdPersonne($personne->getIdPersonne());
    $aArbre = array();
        $aArbreEnfantNiveau1 = array();
            $enfants = PersonneModel::findEnfantParent($personne->getIdPersonne());
            $aEnfants = array();
            if (sizeof($enfants) > 0) {
                foreach($enfants as $enfant){
                    $oEnfant = PersonneModel::readPersonne($enfant);
                    if (isset($oEnfant)) {
                        array_push($aArbreEnfantNiveau1, ArbreController::findArbreDescendenceNiveau1($oEnfant,$facto,$con));
                    }
                        $aArbrePersonneNiveau1 = $aArbreEnfantNiveau1;
                        $aArbrePersonneNiveau1 = array('parent' => $personne, 'enfant' => $aArbrePersonneNiveau1);
                }
            } else {
                $aArbrePersonneNiveau1 = $personne;
            }
    return $aArbrePersonneNiveau1;
    }
}