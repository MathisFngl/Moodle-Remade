<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utilisateur;
use App\Entity\Cours;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
    // Un participant est caractérisé par un id, un utilisateur, et un cours
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_utilisateur", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?Utilisateur $utilisateur = null;  // 🔥 Correction de la relation

    #[ORM\ManyToOne(targetEntity: Cours::class)]
    #[ORM\JoinColumn(name: "code_cours", referencedColumnName: "code", onDelete: "CASCADE")]
    private ?Cours $cours = null;  // 🔥 Correction de la relation

    public function getId(): ?int
    {
        return $this->id;
    }

    // ✅ Getter et setter pour `Utilisateur`
    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    // ✅ Getter et setter pour `Cours`
    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(Cours $cours): static
    {
        $this->cours = $cours;
        return $this;
    }
}
