<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
    ],
    normalizationContext: ['groups' => 'course:read'],
    denormalizationContext: ['groups' => 'course:write'],
)]
#[ApiResource(
    uriTemplate: '/levels/{id}/courses',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            fromProperty: 'courses',
            fromClass: Level::class,
        ),
    ],
    normalizationContext: ['groups' => 'course:read'],
    denormalizationContext: ['groups' => 'course:write'],
)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['course:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['course:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['course:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['course:read'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'pdf')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Level $level = null;

    #[ORM\Column(length: 255)]
    #[Groups(['course:read'])]
    private ?string $pdf = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['course:read'])]
    private ?string $video = null;

    #[ORM\Column(length: 255)]
    #[Groups(['course:read'])]
    private ?string $audio = null;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: CourseContent::class)]
    private Collection $courseContents;

    public function __construct()
    {
        $this->courseContents = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(string $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getAudio(): ?string
    {
        return $this->audio;
    }

    public function setAudio(string $audio): self
    {
        $this->audio = $audio;

        return $this;
    }

    /**
     * @return Collection<int, CourseContent>
     */
    public function getCourseContents(): Collection
    {
        return $this->courseContents;
    }

    public function addCourseContent(CourseContent $courseContent): self
    {
        if (!$this->courseContents->contains($courseContent)) {
            $this->courseContents->add($courseContent);
            $courseContent->setCourse($this);
        }

        return $this;
    }

    public function removeCourseContent(CourseContent $courseContent): self
    {
        if ($this->courseContents->removeElement($courseContent)) {
            // set the owning side to null (unless already changed)
            if ($courseContent->getCourse() === $this) {
                $courseContent->setCourse(null);
            }
        }

        return $this;
    }
}
