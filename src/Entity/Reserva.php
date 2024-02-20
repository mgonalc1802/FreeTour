<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
#[Broadcast]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tour $tour = null;

    #[ORM\OneToOne(inversedBy: 'reserva', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Valoracion $valoracion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hora = null;

    #[ORM\Column]
    private ?int $numeroReservas = null;

    #[ORM\Column(nullable: true)]
    private ?int $numeroAsistidos = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): static
    {
        $this->tour = $tour;

        return $this;
    }

    public function getValoracion(): ?Valoracion
    {
        return $this->valoracion;
    }

    public function setValoracion(Valoracion $valoracion): static
    {
        $this->valoracion = $valoracion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(\DateTimeInterface $hora): static
    {
        $this->hora = $hora;

        return $this;
    }

    public function getNumeroReservas(): ?int
    {
        return $this->numeroReservas;
    }

    public function setNumeroReservas(int $numeroReservas): static
    {
        $this->numeroReservas = $numeroReservas;

        return $this;
    }

    public function getNumeroAsistidos(): ?int
    {
        return $this->numeroAsistidos;
    }

    public function setNumeroAsistidos(?int $numeroAsistidos): static
    {
        $this->numeroAsistidos = $numeroAsistidos;

        return $this;
    }

    public function __toString()
    {
        return "ID: " . $this->getId() . " Fecha: " . $this->getFecha()->format('d-m-Y') . " Hora: " . $this->getHora()->format('H:i');
    }
}
