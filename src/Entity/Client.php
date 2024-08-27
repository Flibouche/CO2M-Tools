<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(length: 14)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $societe = null;

    #[ORM\Column(length: 15)]
    private ?string $telephone = null;

    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'client')]
    private Collection $factures;

    /**
     * @var Collection<int, ContratDeMaintenance>
     */
    #[ORM\OneToMany(targetEntity: ContratDeMaintenance::class, mappedBy: 'client')]
    private Collection $contratDeMaintenances;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
        $this->contratDeMaintenances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setClient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ContratDeMaintenance>
     */
    public function getContratDeMaintenances(): Collection
    {
        return $this->contratDeMaintenances;
    }

    public function addContratDeMaintenance(ContratDeMaintenance $contratDeMaintenance): static
    {
        if (!$this->contratDeMaintenances->contains($contratDeMaintenance)) {
            $this->contratDeMaintenances->add($contratDeMaintenance);
            $contratDeMaintenance->setClient($this);
        }

        return $this;
    }

    public function removeContratDeMaintenance(ContratDeMaintenance $contratDeMaintenance): static
    {
        if ($this->contratDeMaintenances->removeElement($contratDeMaintenance)) {
            // set the owning side to null (unless already changed)
            if ($contratDeMaintenance->getClient() === $this) {
                $contratDeMaintenance->setClient(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom . ' ' . $this->prenom;
    }
}
