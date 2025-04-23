<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Cours;
use App\Entity\Utilisateur;

#[ORM\Entity]
class Participant
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: "integer")]
private int $id;

#[ORM\ManyToOne(targetEntity: Cours::class)]
#[ORM\JoinColumn(name: "id_cours", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
private Cours $cours;

#[ORM\ManyToOne(targetEntity: Utilisateur::class)]
#[ORM\JoinColumn(name: "id_utilisateur", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
private Utilisateur $utilisateur;

// 🔹 Getter et Setter pour id
public function getId(): int
{
return $this->id;
}

// 🔹 Getters et Setters pour la relation avec Cours
public function getCours(): Cours
{
return $this->cours;
}

public function setCours(Cours $cours): void
{
$this->cours = $cours;
}

// 🔹 Getters et Setters pour la relation avec Utilisateur
public function getUtilisateur(): Utilisateur
{
return $this->utilisateur;
}

public function setUtilisateur(Utilisateur $utilisateur): void
{
$this->utilisateur = $utilisateur;
}
}
