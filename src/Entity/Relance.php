<?php

namespace App\Entity;

use App\Repository\RelanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelanceRepository::class)]
class Relance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRelance = null;

    #[ORM\ManyToOne(inversedBy: 'relances')]
    private ?Facture $facture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRelance(): ?\DateTimeInterface
    {
        return $this->dateRelance;
    }

    public function setDateRelance(\DateTimeInterface $dateRelance): static
    {
        $this->dateRelance = $dateRelance;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): static
    {
        $this->facture = $facture;

        return $this;
    }
}
