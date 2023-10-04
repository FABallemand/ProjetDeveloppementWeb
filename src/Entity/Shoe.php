<?php

namespace App\Entity;

use App\Repository\ShoeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoeRepository::class)]
class Shoe
{
    /**
     * @var int Shoe unique ID
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string Shoe brand
     */
    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    /**
     * @var string Shoe model
     */
    #[ORM\Column(length: 255)]
    private ?string $model = null;

    /**
     * @var Cupboard Cupboard where the shoe is stored
     */
    #[ORM\ManyToOne(inversedBy: 'shoes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cupboard $cupboard = null;

    /**
     * @return string String describing the shoes
     */
    public function __toString()
    {
        $s = '';
        $s .=  $this->getBrand() . ' ' . $this->getModel();
        // $s .= $this->getId() . ' ' . $this->getBrand() . ' ' . $this->getModel();
        return $s;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getCupboard(): ?Cupboard
    {
        return $this->cupboard;
    }

    public function setCupboard(?Cupboard $cupboard): static
    {
        $this->cupboard = $cupboard;

        return $this;
    }
}
