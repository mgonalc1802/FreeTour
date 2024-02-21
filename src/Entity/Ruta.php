<?php

namespace App\Entity;

use App\Repository\RutaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: RutaRepository::class)]
#[Broadcast]
class Ruta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $coordenadaInicio = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $urlFoto = null;

    #[ORM\ManyToMany(targetEntity: Item::class, inversedBy: 'rutas')]
    private Collection $item;

    #[ORM\Column]
    private ?int $aforo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $programacion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaComienzo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaFin = null;

    #[ORM\OneToMany(mappedBy: 'ruta_id', targetEntity: Tour::class)]
    private Collection $tours;

    public function __construct()
    {
        $this->item = new ArrayCollection();
        $this->tours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getCoordenadaInicio(): ?string
    {
        return $this->coordenadaInicio;
    }

    public function setCoordenadaInicio(string $coordenadaInicio): static
    {
        $this->coordenadaInicio = $coordenadaInicio;

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

    public function getUrlFoto(): ?string
    {
        return $this->urlFoto;
    }

    public function setUrlFoto(string $urlFoto): static
    {
        $this->urlFoto = $urlFoto;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    public function addItem(Item $item): static
    {
        if (!$this->item->contains($item)) {
            $this->item->add($item);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        $this->item->removeElement($item);

        return $this;
    }

    public function getAforo(): ?int
    {
        return $this->aforo;
    }

    public function setAforo(int $aforo): static
    {
        $this->aforo = $aforo;

        return $this;
    }

    public function getProgramacion(): ?string
    {
        return $this->programacion;
    }

    public function setProgramacion(?string $programacion): static
    {
        $this->programacion = $programacion;

        return $this;
    }

    public function getFechaComienzo(): ?\DateTimeInterface
    {
        return $this->fechaComienzo;
    }

    public function setFechaComienzo(\DateTimeInterface $fechaComienzo): static
    {
        $this->fechaComienzo = $fechaComienzo;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTimeInterface $fechaFin): static
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * @return Collection<int, Tour>
     */
    public function getTours(): Collection
    {
        return $this->tours;
    }

    public function addTour(Tour $tour): static
    {
        if (!$this->tours->contains($tour)) {
            $this->tours->add($tour);
            $tour->setRutaId($this);
        }

        return $this;
    }

    public function removeTour(Tour $tour): static
    {
        if ($this->tours->removeElement($tour)) {
            // set the owning side to null (unless already changed)
            if ($tour->getRutaId() === $this) {
                $tour->setRutaId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titulo;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'titulo' => $this->getTitulo(),
            'coordenadaInicio' => $this->getCoordenadaInicio(),
            'descripcion' => $this->getDescripcion(),
            'urlFoto' => $this->getUrlFoto(),
            'aforo' => $this->getAforo(),
            'fechaComienzo' => $this->getFechaComienzo()->format("Y-m-d"),
            'fechaFin' => $this->getFechaFin()->format("Y-m-d")
        ];
    }
}
