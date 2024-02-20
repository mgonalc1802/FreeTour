<?php

namespace App\Entity;

use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: TourRepository::class)]
#[Broadcast]
class Tour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hora = null;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\OneToOne(inversedBy: 'tour', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Informe $informe = null;

    #[ORM\Column(length: 255)]
    private ?string $guia = null;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    private ?Ruta $ruta_id = null;

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Reserva>
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(Reserva $reserva): static
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas->add($reserva);
            $reserva->setTour($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): static
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getTour() === $this) {
                $reserva->setTour(null);
            }
        }

        return $this;
    }

    public function getInforme(): ?Informe
    {
        return $this->informe;
    }

    public function setInforme(Informe $informe): static
    {
        $this->informe = $informe;

        return $this;
    }

    public function getGuia(): ?string
    {
        return $this->guia;
    }

    public function setGuia(string $guia): static
    {
        $this->guia = $guia;

        return $this;
    }

    public function getRutaId(): ?Ruta
    {
        return $this->ruta_id;
    }

    public function setRutaId(?Ruta $ruta_id): static
    {
        $this->ruta_id = $ruta_id;

        return $this;
    }

    public function __toString()
    {
        return $this->fecha->format('d-m-Y') . ' ';
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getRutaId()->getTitulo(),
            'start' => $this->getFecha()->format('Y-m-d') . ' ' . $this->getHora()->format('H:i:s'),
            'description' => $this->getRutaId()->getAforo(),
            'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF))
        ];
    }
}
