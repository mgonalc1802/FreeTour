<?php

namespace App\Entity;

use App\Repository\InformeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: InformeRepository::class)]
#[Broadcast]
class Informe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $participantes = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $dineroRecaudado = null;

    #[ORM\Column(length: 255)]
    private ?string $fotoGrupal = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\OneToOne(mappedBy: 'informe', cascade: ['persist', 'remove'])]
    private ?Tour $tour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipantes(): ?int
    {
        return $this->participantes;
    }

    public function setParticipantes(int $participantes): static
    {
        $this->participantes = $participantes;

        return $this;
    }

    public function getDineroRecaudado(): ?string
    {
        return $this->dineroRecaudado;
    }

    public function setDineroRecaudado(string $dineroRecaudado): static
    {
        $this->dineroRecaudado = $dineroRecaudado;

        return $this;
    }

    public function getFotoGrupal(): ?string
    {
        return $this->fotoGrupal;
    }

    public function setFotoGrupal(string $fotoGrupal): static
    {
        $this->fotoGrupal = $fotoGrupal;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(Tour $tour): static
    {
        // set the owning side of the relation if necessary
        if ($tour->getInforme() !== $this) {
            $tour->setInforme($this);
        }

        $this->tour = $tour;

        return $this;
    }
}
