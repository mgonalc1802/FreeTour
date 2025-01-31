<?php

namespace App\Entity;

use App\Repository\ProvinciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ProvinciaRepository::class)]
#[Broadcast]
class Provincia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\OneToMany(mappedBy: 'provincia', targetEntity: Localidad::class, orphanRemoval: true)]
    private Collection $localidad;

    public function __construct()
    {
        $this->localidad = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Localidad>
     */
    public function getLocalidad(): Collection
    {
        return $this->localidad;
    }

    public function addLocalidad(Localidad $localidad): static
    {
        if (!$this->localidad->contains($localidad)) {
            $this->localidad->add($localidad);
            $localidad->setProvincia($this);
        }

        return $this;
    }

    public function removeLocalidad(Localidad $localidad): static
    {
        if ($this->localidad->removeElement($localidad)) {
            // set the owning side to null (unless already changed)
            if ($localidad->getProvincia() === $this) {
                $localidad->setProvincia(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre;
    }
}
