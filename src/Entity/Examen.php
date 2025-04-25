<?php
namespace App\Entity;

use App\Repository\ExamenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenRepository::class)]
class Examen
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $id = null;

#[ORM\ManyToOne(targetEntity: Cours::class)]
#[ORM\JoinColumn(name: "code_cours", referencedColumnName: "code", onDelete: "CASCADE")]
private ?Cours $cours = null;

#[ORM\Column(length: 255)]
private ?string $titre = null;

#[ORM\Column(type: Types::TEXT, nullable: true)]
private ?string $description = null;

#[ORM\Column(nullable: true)]
private ?int $coeff = null;

#[ORM\Column(nullable: false)]
private ?int $bareme = null;

public function getId(): ?int
{
return $this->id;
}

public function getCours(): ?Cours
{
return $this->cours;
}

public function setCours(?Cours $cours): static
{
$this->cours = $cours;
return $this;
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

public function getDescription(): ?string
{
return $this->description;
}

public function setDescription(?string $description): static
{
$this->description = $description;
return $this;
}

public function getCoeff(): ?int
{
return $this->coeff;
}

public function setCoeff(?int $coeff): static
{
$this->coeff = $coeff;
return $this;
}

public function getBareme(): ?int
{
return $this->bareme;
}

public function setBareme(int $bareme): static
{
$this->bareme = $bareme;
return $this;
}
}
