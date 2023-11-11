<?php

namespace App\Entity;

use App\Repository\ShelfRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: ShelfRepository::class)]
#[ApiResource]
class Shelf
{
    /**
     * @var int Shelf unique ID
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string Shelf name
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string Shelf description
     */
    #[ORM\Column(length: 255)]
    private ?string $description = null; /* idealy change for text type */

    /**
     * @var bool Indicates if the shelf has been published
     */
    #[ORM\Column]
    private ?bool $published = null;

    /**
     * @var Member Shelf owner
     */
    #[ORM\ManyToOne(inversedBy: 'shelves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $member = null;

    /**
     * @var Collection Shoes displayed on the shelf
     */
    #[ORM\ManyToMany(targetEntity: Shoe::class, inversedBy: 'shelves')]
    private Collection $shoes;

    /**
     * @var DateTimeInterface Date when the shelf was created
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    /**
     * @var DateTimeInterface Date when the shelf was last updated
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated = null;

    public function __construct()
    {
        $this->shoes = new ArrayCollection();
    }

    /**
     * @return string String describing the shelf
     */
    public function __toString()
    {
        $s = '';
        $s .=  $this->getName();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): static
    {
        $this->member = $member;

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
        }

        return $this;
    }

    public function removeShoe(Shoe $shoe): static
    {
        $this->shoes->removeElement($shoe);

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): static
    {
        $this->updated = $updated;

        return $this;
    }
}
