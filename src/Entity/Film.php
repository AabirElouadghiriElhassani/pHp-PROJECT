<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    /**
     * @var Collection<int, Creneau>
     */
    #[ORM\OneToMany(targetEntity: Creneau::class, mappedBy: 'film')]
    private Collection $creneauss;

    public function __construct()
    {
        $this->creneauss = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Creneau>
     */
    public function getCreneauss(): Collection
    {
        return $this->creneauss;
    }

    public function addCreneauss(Creneau $creneauss): static
    {
        if (!$this->creneauss->contains($creneauss)) {
            $this->creneauss->add($creneauss);
            $creneauss->setFilm($this);
        }

        return $this;
    }

    public function removeCreneauss(Creneau $creneauss): static
    {
        if ($this->creneauss->removeElement($creneauss)) {
            // set the owning side to null (unless already changed)
            if ($creneauss->getFilm() === $this) {
                $creneauss->setFilm(null);
            }
        }

        return $this;
    }
}
