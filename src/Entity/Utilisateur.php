<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $mot_de_passe = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column]
    private ?bool $admin = null;

    #[ORM\Column(nullable: true)]
    private ?string $photo = null;

    #[ORM\OneToMany(targetEntity: Participant::class, mappedBy: "utilisateur", cascade: ["persist", "remove"])]
    private Collection $participants; // ✅ Ajout de la relation

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function getEmail(): ?string
    {
        return $this->mail;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setUtilisateur($this);
        }
        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            if ($participant->getUtilisateur() === $this) {
                $participant->setUtilisateur(null);
            }
        }
        return $this;
    }
}
