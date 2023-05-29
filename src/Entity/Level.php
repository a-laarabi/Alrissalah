<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\LevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LevelRepository::class)]
//#[ApiResource]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    normalizationContext: ['groups' => 'level:read'],
    denormalizationContext: ['groups' => 'level:write']
)]
class Level
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['level:read', 'level:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['level:read', 'level:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['level:read', 'level:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['level:read', 'level:write'])]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['level:read', 'level:write'])]
    private ?string $manuel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['level:read', 'level:write'])]
    private ?string $vocabulary = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['level:read', 'level:write'])]
    private ?string $content = null;

    #[ORM\OneToMany(mappedBy: 'level', targetEntity: Course::class)]
    private Collection $courses;


    #[Groups(['level:read'])]
    private ?float $completed = null;


    #[Groups(['level:read'])]
    private ?bool $payed = null;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getManuel(): ?string
    {
        return $this->manuel;
    }

    public function setManuel(?string $manuel): self
    {
        $this->manuel = $manuel;

        return $this;
    }

    public function getVocabulary(): ?string
    {
        return $this->vocabulary;
    }

    public function setVocabulary(?string $vocabulary): self
    {
        $this->vocabulary = $vocabulary;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourses(Course $courses): self
    {
        if (!$this->courses->contains($courses)) {
            $this->courses->add($courses);
            $courses->setLevel($this);
        }

        return $this;
    }

    public function removeCourses(Course $courses): self
    {
        if ($this->courses->removeElement($courses)) {
            // set the owning side to null (unless already changed)
            if ($courses->getLevel() === $this) {
                $courses->setLevel(null);
            }
        }

        return $this;
    }

    public function getCompleted(): ?float
    {
        return $this->completed;
    }

    public function setCompleted(float $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    public function getPayed(): ?bool
    {
        return $this->payed;
    }

    public function setPayed(bool $payed): self
    {
        $this->payed = $payed;

        return $this;
    }
}
