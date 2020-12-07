<?php

/*
 * Ceci sera ajouté dans tous vos fichiers PHP en entête.
 *
 * (c) Zozor <zozor@openclassrooms.com>
 *
 * A adapter et ré-utiliser selon vos besoins!
 */

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonneRepository::class)
 */
class Personne
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Un nom est obligatoire")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Un prénom est obligatoire")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surnom;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank
     */
    private $typeAscendence;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parent1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parent2;

    /**
     * @ORM\Column(type="integer")
     */
    private $validee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPrenom2(): ?string
    {
        return $this->prenom2;
    }

    public function setPrenom2(?string $prenom2): self
    {
        $this->prenom2 = $prenom2;

        return $this;
    }

    public function getPrenom3(): ?string
    {
        return $this->prenom3;
    }

    public function setPrenom3(?string $prenom3): self
    {
        $this->prenom3 = $prenom3;

        return $this;
    }

    public function getPrenom4(): ?string
    {
        return $this->prenom4;
    }

    public function setPrenom4(?string $prenom4): self
    {
        $this->prenom4 = $prenom4;

        return $this;
    }

    public function getSurnom(): ?string
    {
        return $this->surnom;
    }

    public function setSurnom(?string $surnom): self
    {
        $this->surnom = $surnom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getTypeAscendence(): ?string
    {
        return $this->typeAscendence;
    }

    public function setTypeAscendence(string $typeAscendence): self
    {
        $this->typeAscendence = $typeAscendence;

        return $this;
    }

    public function getParent1(): ?int
    {
        return $this->parent1;
    }

    public function setParent1(?int $parent1): self
    {
        $this->parent1 = $parent1;

        return $this;
    }

    public function getParent2(): ?int
    {
        return $this->parent2;
    }

    public function setParent2(?int $parent2): self
    {
        $this->parent2 = $parent2;

        return $this;
    }

    public function getValidee(): ?int
    {
        return $this->validee;
    }

    public function setValidee(int $validee): self
    {
        $this->validee = $validee;

        return $this;
    }

    public function __toString(): string
    {
        $stringPersonne = '';
        $stringPersonne = "$this->prenom $this->prenom2 $this->prenom3 $this->prenom4 ".mb_strtoupper($this->nom);
        if (null !== $this->surnom) {
            $stringPersonne .= ' dit '.$this->surnom;
        }

        return $stringPersonne;
    }
}
