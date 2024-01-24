<?php

namespace App\Entity;

use App\Repository\ValoracionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ValoracionRepository::class)]
#[Broadcast]
class Valoracion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $guia = null;

    #[ORM\Column]
    private ?int $ruta = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comentarios = null;

    #[ORM\OneToOne(mappedBy: 'valoracion', cascade: ['persist', 'remove'])]
    private ?Reserva $reserva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuia(): ?int
    {
        return $this->guia;
    }

    public function setGuia(int $guia): static
    {
        $this->guia = $guia;

        return $this;
    }

    public function getRuta(): ?int
    {
        return $this->ruta;
    }

    public function setRuta(int $ruta): static
    {
        $this->ruta = $ruta;

        return $this;
    }

    public function getComentarios(): ?string
    {
        return $this->comentarios;
    }

    public function setComentarios(?string $comentarios): static
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    public function getReserva(): ?Reserva
    {
        return $this->reserva;
    }

    public function setReserva(Reserva $reserva): static
    {
        // set the owning side of the relation if necessary
        if ($reserva->getValoracion() !== $this) {
            $reserva->setValoracion($this);
        }

        $this->reserva = $reserva;

        return $this;
    }
}
