<?php

namespace App\Entity;

use App\Repository\CupboardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CupboardRepository::class)]
class Cupboard
{
    /**
     * @var int Cupboard unique ID
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string Cupboard name
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'cupboard', targetEntity: Shoe::class, cascade: ['persist'])]
    private Collection $shoes;

    public function __construct()
    {
        $this->shoes = new ArrayCollection();
    }

    /**
     * @return string String describing the cupboard
     */
    public function __toString()
    {
        $s = '';
        $s .= $this->getId() . ' ' . $this->getName();
        return $s;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Shoe>
     */
    public function getShoes(): Collection
    {
        return $this->shoes;
    }

    public function addShoe(Shoe $shoe): static
    {
        if (!$this->shoes->contains($shoe)) {
            $this->shoes->add($shoe);
            $shoe->setCupboard($this);
        }

        return $this;
    }

    public function removeShoe(Shoe $shoe): static
    {
        if ($this->shoes->removeElement($shoe)) {
            // set the owning side to null (unless already changed)
            if ($shoe->getCupboard() === $this) {
                $shoe->setCupboard(null);
            }
        }

        return $this;
    }
}