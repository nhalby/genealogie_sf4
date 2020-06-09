<?php

namespace App\Entity;

use App\Repository\ParentaliteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParentaliteRepository::class)
 */
class Parentalite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $IdParent;

    /**
     * @ORM\Column(type="integer")
     */
    private $IdPersonne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdParent(): ?int
    {
        return $this->IdParent;
    }

    public function setIdParent(int $IdParent): self
    {
        $this->IdParent = $IdParent;

        return $this;
    }

    public function getIdPersonne(): ?int
    {
        return $this->IdPersonne;
    }

    public function setIdPersonne(int $IdPersonne): self
    {
        $this->IdPersonne = $IdPersonne;

        return $this;
    }
}
