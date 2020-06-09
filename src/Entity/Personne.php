<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Prenom2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Prenom3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Prenom4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Surnom;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $Sexe;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $TypeAscendence;

    /**
     * @ORM\Column(type="integer")
     */
    private $Parent1;

    /**
     * @ORM\Column(type="integer")
     */
    private $Parent2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getPrenom2(): ?string
    {
        return $this->Prenom2;
    }

    public function setPrenom2(?string $Prenom2): self
    {
        $this->Prenom2 = $Prenom2;

        return $this;
    }

    public function getPrenom3(): ?string
    {
        return $this->Prenom3;
    }

    public function setPrenom3(?string $Prenom3): self
    {
        $this->Prenom3 = $Prenom3;

        return $this;
    }

    public function getPrenom4(): ?string
    {
        return $this->Prenom4;
    }

    public function setPrenom4(?string $Prenom4): self
    {
        $this->Prenom4 = $Prenom4;

        return $this;
    }

    public function getSurnom(): ?string
    {
        return $this->Surnom;
    }

    public function setSurnom(?string $Surnom): self
    {
        $this->Surnom = $Surnom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->Sexe;
    }

    public function setSexe(string $Sexe): self
    {
        $this->Sexe = $Sexe;

        return $this;
    }

    public function getTypeAscendence(): ?string
    {
        return $this->TypeAscendence;
    }

    public function setTypeAscendence(string $TypeAscendence): self
    {
        $this->TypeAscendence = $TypeAscendence;

        return $this;
    }

    public function getParent1(): ?int
    {
        return $this->Parent1;
    }

    public function setParent1(int $Parent1): self
    {
        $this->Parent1 = $Parent1;

        return $this;
    }

    public function getParent2(): ?int
    {
        return $this->Parent2;
    }

    public function setParent2(int $Parent2): self
    {
        $this->Parent2 = $Parent2;

        return $this;
    }
}
