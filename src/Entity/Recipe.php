<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection; // 🌟 Indispensable pour les relations ManyToMany
use Doctrine\Common\Collections\Collection;      // 🌟 Indispensable pour les relations ManyToMany
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // 🌟 Indispensable pour les validations du TP

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 1, max: 1440)]
    private ?int $time = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 1, max: 50)]
    private ?int $nbPersons = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 1, max: 5)]
    private ?int $difficulty = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero]
    #[Assert\LessThan(1000)]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $isFavorite = false;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class)]
    private Collection $ingredients; // 🌟 On utilise bien 'Collection' ici pour Doctrine

    // 🌟 LE CONSTRUCTEUR MAGIQUE POUR INITIALISER LA DATE ET LA COLLECTION
    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable(); // Règle la date automatiquement
    }

    // --- GETTERS ET SETTERS RECOMPOSÉS PROPREMENT ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): static
    {
        $this->time = $time;
        return $this;
    }

    public function getNbPersons(): ?int
    {
        return $this->nbPersons;
    }

    public function setNbPersons(?int $nbPersons): static
    {
        $this->nbPersons = $nbPersons;
        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty): static
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function isFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): static
    {
        $this->isFavorite = $isFavorite;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }
        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);
        return $this;
    }
}