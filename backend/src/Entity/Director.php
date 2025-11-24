<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource]
class Director
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    #[Groups(['movie:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read'])]
    private ?string $firstname = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dod = null;

    #[ORM\OneToMany(mappedBy: 'director', targetEntity: Movie::class)]
    private iterable $movies; // ⚠️ PAS DE Groups → PAS DE BOUCLE

    public function getId(): ?int { return $this->id; }
}
