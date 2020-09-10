<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandesRepository::class)
 */
class Commandes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_commande;

    /**
     * @ORM\Column(type="string")
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="commande")
     */
    private $user;

    /**
     * @ORM\Column(type="array")
     */
    private $commande = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->reference;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): self
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCommande(): ?array
    {
        return $this->commande;
    }

    public function setCommande(array $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
