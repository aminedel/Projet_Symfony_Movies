<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups; // <--- L'import important

#[ORM\Entity]
#[ApiResource]
class Actor
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    #[Groups(['movie:read'])] // Visible dans le film
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['movie:read'])] // Visible dans le film
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['movie:read'])] // Visible dans le film
    private ?string $firstname = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $bio = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'actors')]
    // PAS DE GROUPS ICI ! C'est ça qui évite la boucle infinie
    private Collection $movies;

    public function __construct() { $this->movies = new ArrayCollection(); }

    public function getId(): ?int { return $this->id; }
    public function getLastname(): ?string { return $this->lastname; }
    public function setLastname(string $l): static { $this->lastname = $l; return $this; }
    public function getFirstname(): ?string { return $this->firstname; }
    public function setFirstname(?string $f): static { $this->firstname = $f; return $this; }
    public function getDob(): ?\DateTimeInterface { return $this->dob; }
    public function setDob(?\DateTimeInterface $d): static { $this->dob = $d; return $this; }
    public function addMovie(Movie $m): static { if (!$this->movies->contains($m)) { $this->movies->add($m); $m->addActor($this); } return $this; }
}
