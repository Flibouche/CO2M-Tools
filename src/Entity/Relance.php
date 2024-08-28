<?php

namespace App\Entity;

use App\Enum\TypeRelance;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RelanceRepository;

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
    #[ORM\JoinColumn(nullable: false)]
    private Facture $facture;

    #[ORM\Column(type: 'string', enumType: TypeRelance::class)]
    private ?TypeRelance $typeRelance = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

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

    public function getTypeRelance(): ?TypeRelance
    {
        return $this->typeRelance;
    }

    public function setTypeRelance(TypeRelance $typeRelance): self
    {
        $this->typeRelance = $typeRelance;

        return $this;
    }

    public function getRelanceAsString(): ?string
    {
        return $this->typeRelance->value;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
