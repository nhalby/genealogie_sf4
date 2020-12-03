<?php

/*
 * Ceci sera ajouté dans tous vos fichiers PHP en entête.
 *
 * (c) Zozor <zozor@openclassrooms.com>
 *
 * A adapter et ré-utiliser selon vos besoins!
 */

namespace App\Repository;

use App\Entity\Parentalite;
use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    // /**
    //  * @return Personne[] Returns an array of Personne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Personne
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllPersonnes()
    {
        return $this->createQueryBuilder('p')
        ->andWhere('p.validee = 1')
        ->orderBy('p.prenom, p.prenom2, p.prenom3, p.prenom4, p.nom', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function findEnfantParent($parentalite)
    {
        //$rows1 = $personneRepo = $this->getDoctrine()->getRepository(Parentalite::class)->findEnfantParent($parentalite);
        $rows1 = $this->createQueryBuilder('p')
        ->select('pa.idPersonne')
        ->innerJoin('App\Entity\Parentalite', 'pa', Join::WITH, 'p.id = pa.idParent')
        ->where('pa.idParent=:parentalite')
        ->setParameter('parentalite', $parentalite)
        ->getQuery()
        ->getResult();

        $rows = $this->createQueryBuilder('p')
        ->where('p.id in (:rows1)')
        ->setParameter('rows1', $rows1)
        ->orderBy('p.id', 'ASC')
        ->getQuery()
        ->getResult();

        if (0 === \count($rows)) {
            return null;
        }

        return $rows;
    }

    public static function findPersonneByIdPersonne($idPersonne)
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :val')
            ->setParameter('val', $idPersonne)
            ->andWhere('p.validee = 1')
            ->getQuery()
            ->execute();
    }

    /*public static function findAllPersonnesByCritere($cri) {

        return $this->createQueryBuilder('p')
        ->andWhere('p.validee = 1')
        ->orderBy('p.prenom, p.prenom2, p.prenom3, p.prenom4, nom', 'ASC')
        ->getQuery()
        ->getResult();

        $requete = $mysqli->query("SELECT p.* from personne p where p.validee = 1 and (prenom like '%$cri%' or prenom2 like '%$cri%' or prenom3 like '%$cri%' or prenom4 like '%$cri%' or nom like '%$cri%'  or surnom like '%$cri%') order by p.surnom, p.prenom, p.prenom2, p.prenom3, p.prenom4, nom ASC LIMIT 10");
        $rows = PersonneModel::resultToArray($requete);

        return $rows;
    }

    public static function findByNomComplet($cri, $mysqli) {
    $requete =$mysqli->query("SELECT p.* from personne p where CONCAT(p.prenom, ' ', p.prenom2, ' ', p.prenom3, ' ',p.prenom4, ' ', p.nom, ' dit ', p.surnom) = '$cri'");
        $row = PersonneModel::resultToArray($requete);
        if(sizeof($row) > 0) {
            $row = PersonneModel::readPersonne($row[0]);
            return $row;
        } else {
            return new Personne();
        }
    }

    public static function findPersonneByIdPersonne($idPersonne, $mysqli) {
        $requete =$mysqli->query("SELECT p.* from personne p where p.idPersonne=$idPersonne and p.validee = 1");
        $row = PersonneModel::resultToArray($requete);
        if(sizeof($row) > 0) {
            $row = PersonneModel::readPersonne($row[0]);
            return $row;
        } else {
            return new Personne();
        }

    }


    public static function findDescendancePersonne($personne, $mysqli) {

        $requete =$mysqli->query("SELECT p.* from personne p inner join parentalite pa on (p.idPersonne=pa.idPersonne) where pa.idPersonne=$personne)");
        $rows = PersonneModel::resultToArray($requete);

        return $row;
    }


    public static function findAscendancePersonne($personne, $mysqli) {

        $requete =$mysqli->query("SELECT * from personne p where p.idPersonne in (SELECT pa.idparent from personne p inner join parentalite pa on (p.idPersonne=pa.idparent) where pa.idPersonne=$personne)");
        $rows = PersonneModel::resultToArray($requete);

        return $rows;
    }

    public static function findAscendancePereOuMerePersonne($personne, $sexeparentalite, $mysqli) {
        $ascendence = $this->findAscendancePersonne($personne);

        foreach($ascendence as $parentalite) {
            if($parentalite->getSexe() === $sexeparentalite) {
                return $parentalite;
            }
        }
        return null;
    }

    public static function findEnfantPersonne($parentalite, $mysqli){

        $requete =$mysqli->query("SELECT * from personne p where idPersonne in (SELECT pa.idPersonne from personne p inner join parentalite pa on (p.idPersonne=pa.idparent) where idparent=$parentalite)");
        $rows = PersonneModel::resultToArray($requete);

        return $rows;

    }

    public static function isConcubin($parentalite1, $parentalite2, $mysqli) {

        $requete =$mysqli->query("SELECT * from concubin p where idMari in ($parentalite2, $parentalite1) and idFemme in ($parentalite1, $parentalite2)");
        $rows = PersonneModel::resultToArray($requete);

        if(sizeof($rows)>0)
            return true;
        else
            return false;
    }

    public static function findEnfantParents($parentalite1, $parentalite2, $mysqli){

        $requete =$mysqli->query("SELECT * from personne p where idPersonne in (SELECT pa.idPersonne from personne p inner join parentalite pa on (p.idPersonne=pa.idparent) where idparent=$parentalite1) and idPersonne in (SELECT pa.idPersonne from personne p inner join parentalite pa on (p.idPersonne=pa.idparent) where idparent=$parentalite2) ORDER BY IDPERSONNE");
        $rows = PersonneModel::resultToArray($requete);

        if (sizeof($rows) == 0)
            return null;
        else
            return $rows;

    }

    public static function findEnfantParent($parentalite, $mysqli){

        $requete =$mysqli->query("SELECT * from personne p where idPersonne in (SELECT pa.idPersonne from personne p inner join parentalite pa on (p.idPersonne=pa.idparent) where idparent=$parentalite) ORDER BY IDPERSONNE");
        $rows = PersonneModel::resultToArray($requete);

        if (sizeof($rows) == 0)
            return null;
        else
            return $rows;

    }

    public static function findParentsEnfant($enfant, $mysqli){

        $requete =$mysqli->query("SELECT * from personne p where idPersonne in (SELECT pa.idParent from personne p inner join parentalite pa on (p.idPersonne=pa.idpersonne) where pa.idpersonne=$enfant)");
        $rows = PersonneModel::resultToArray($requete);

        if (sizeof($rows) == 0)
            return null;
        else
            return $rows;

    }

    public static function findFraternitePersonne($personne, $mysqli) {
        $parentalites = $this->findAscendancePersonne($personne);
        return $this->findEnfantPersonne($parentalites[1], $parentalites[2]);
    }

    public static function isEnfantPersonne($parentalite, $personne, $mysqli) {

        $requete =$mysqli->query("SELECT count(*) from personne p inner join parentalite pa on (p.idPersonne=pa.idPersonne) where idparent=$parentalite and p.idPersonne=$personne");
        $rows = PersonneModel::resultToArray($requete);

        return $rows;
    }

    public static function findConcubin($personne, $sexe, $mysqli, $biSexuel = false) {

        $listeConcubin = array();

        if($sexe === 'M' || $biSexuel) {
            $requete =$mysqli->query("SELECT c.* from concubin c where c.idMari=$personne");
            $rows = PersonneModel::resultToArray($requete);
            if($rows!=null) {
                foreach($rows as $row){
                    $row = PersonneModel::findPersonneByIdPersonne($row['IDFEMME'], $mysqli);
                    array_push($listeConcubin, $row);
                }
            }

        }
        if($sexe === 'F' || $biSexuel) {
            $requete =$mysqli->query("SELECT c.* FROM concubin c Where c.idFemme=$personne");
            $rows = PersonneModel::resultToArray($requete);
            if($rows!=null) {
                foreach($rows as $row){
                    $row = PersonneModel::findPersonneByIdPersonne($row['IDMARI'], $mysqli);
                    array_push($listeConcubin, $row);
                }
            }
        }
        if(sizeof($listeConcubin)>0)
            return $listeConcubin;
        else
            return null;
    }

    public static function findAscendanceBeauPereOuBelleMerePersonne($personne, $parentalite, $mysqli, $biSexuel = false, $sexeBeauparentalite) {

        $listeConcubin = $this->findConcubin($parentalite, $biSexuel, $mysqli);
        $listeBeauparentalite = array();

        foreach($listeConcubin as $mysqlicubin){
            if (!isEnfantPersonne($parentalite, $personne)) {
                if(isset($sexeBeauparentalite) && $sexeBeauparentalite === $mysqlicubin->getSexe()){
                    array_push($listeBeauparentalite,$mysqlicubin);
                } elseif (!isset($sexeBeauparentalite)) {
                    array_push($listeBeauparentalite,$mysqlicubin);
                }
            }
        }

        if(sizeof($listeBeauparentalite)>0)
            return $listeBeauparentalite;
        else
            return null;
    }

    public static function asBeauPereOuBelleMerePersonne($personne, $parentalite, $mysqli){
        if(sizeof(findAscendanceBeauPereOuBelleMerePersonne($personne, $parentalite, true))>0)
            return true;
        else
            return false;
    }

    public static function read($idPers,$mysqli){
        $requete =$mysqli->query("SELECT * from personne p where p.idPersonne=".$idPers->getIdPersonne());
        $rows = PersonneModel::resultToArray($requete);
        $pers = new Personne();
        if(sizeof($rows)>0)
            $pers = PersonneModel::readPersonne($rows[0]);
        return $pers;
    }

    public static function readPersonne($aPersonn){
        $personne = new Personne();
        $pers = $personne->createPersonneComplete($aPersonn['IDPERSONNE'],$aPersonn['NOM'],$aPersonn['PRENOM'],$aPersonn['PRENOM2'],$aPersonn['PRENOM3'],$aPersonn['PRENOM4'],$aPersonn['SURNOM'],$aPersonn['SEXE'],$aPersonn['TYPEASCENDENCE'],$aPersonn['PARENT1'],$aPersonn['PARENT2'],$aPersonn['VALIDEE'],$aPersonn['REDACTEUR']);
        return $pers;
    }

    public static function findPersonnesByValidee($val, $mysqli){
        $requete =$mysqli->query("SELECT * from personne p where validee=$val");
        $rows = PersonneModel::resultToArray($requete);

        return $rows;
    }
    */
}
