<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    /**
     * @var int Member unique ID
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string Member name
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var int Member age
     */
    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    /**
     * @var Collection Cupboards owned by the member
     */
    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Cupboard::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $cupboards;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Shelf::class, orphanRemoval: true, cascade: ['persist'])] 
    private Collection $shelves;

    #[ORM\OneToOne(mappedBy: 'member', cascade: ['persist', 'remove'])]
    // #[ORM\JoinColumn(nullable: true)] ??
    private ?User $user = null;

    public function __construct()
    {
        $this->cupboards = new ArrayCollection();
        $this->shelves = new ArrayCollection();
    }

    /**
     * @return string String describing the member
     */
    public function __toString()
    {
        $s = '';
        $s .= $this->getName();
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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection<int, Cupboard>
     */
    public function getCupboards(): Collection
    {
        return $this->cupboards;
    }

    public function addCupboard(Cupboard $cupboard): static
    {
        if (!$this->cupboards->contains($cupboard)) {
            $this->cupboards->add($cupboard);
            $cupboard->setMember($this);
        }

        return $this;
    }

    public function removeCupboard(Cupboard $cupboard): static
    {
        if ($this->cupboards->removeElement($cupboard)) {
            // set the owning side to null (unless already changed)
            if ($cupboard->getMember() === $this) {
                $cupboard->setMember(null);
            }
        }

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
            $shelf->setMember($this);
        }

        return $this;
    }

    public function removeShelf(Shelf $shelf): static
    {
        if ($this->shelves->removeElement($shelf)) {
            // set the owning side to null (unless already changed)
            if ($shelf->getMember() === $this) {
                $shelf->setMember(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        // set the owning side of the relation if necessary
        if ($user->getMember() !== $this) {
            $user->setMember($this);
        }

        $this->user = $user;

        return $this;
    }
}
