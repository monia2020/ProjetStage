<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=140)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=SousCategories::class, mappedBy="categorie", orphanRemoval=true)
     */
    private $sousCategory;

    /**
     * @ORM\OneToMany(targetEntity=Produits::class, mappedBy="categorie", orphanRemoval=true)
     */
    private $produits;


    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->sousCategory = new ArrayCollection();   
    }

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


    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return Collection|Produits[]
     */
    public function getProduits()
    {
        return $this->produits;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SousCategories[]
     */
    public function getSousCategory(): Collection
    {
        return $this->sousCategory;
    }

    public function addSousCategory(SousCategories $sousCategory): self
    {
        if (!$this->sousCategory->contains($sousCategory)) {
            $this->sousCategory[] = $sousCategory;
            $sousCategory->setCategorie($this);
        }

        return $this;
    }

    public function removeSousCategory(SousCategories $sousCategory): self
    {
        if ($this->sousCategory->contains($sousCategory)) {
            $this->sousCategory->removeElement($sousCategory);
            // set the owning side to null (unless already changed)
            if ($sousCategory->getCategorie() === $this) {
                $sousCategory->setCategorie(null);
            }
        }

        return $this;
    }
}