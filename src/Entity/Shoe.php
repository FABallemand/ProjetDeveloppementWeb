<?php

namespace App\Entity;

use App\Repository\ShoeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: ShoeRepository::class)]
#[ApiResource]
#[Vich\Uploadable]
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

    #[ORM\ManyToMany(targetEntity: Shelf::class, mappedBy: 'shoes')]
    private Collection $shelves;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $purchased = null;

    #[Vich\UploadableField(mapping: 'shoe', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->shelves = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Shelf>
     */
    public function getShelves(): Collection
    {
        return $this->shelves;
    }

    public function addShelf(Shelf $shelf): static
    {
        if (!$this->shelves->contains($shelf)) {
            $this->shelves->add($shelf);
            $shelf->addShoe($this);
        }

        return $this;
    }

    public function removeShelf(Shelf $shelf): static
    {
        if ($this->shelves->removeElement($shelf)) {
            $shelf->removeShoe($this);
        }

        return $this;
    }

    public function getPurchased(): ?\DateTimeInterface
    {
        return $this->purchased;
    }

    public function setPurchased(?\DateTimeInterface $purchased): static
    {
        $this->purchased = $purchased;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }
}
