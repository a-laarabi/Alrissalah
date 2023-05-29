<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\CourseContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CourseContentRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
    ],
    normalizationContext: ['groups' => 'course:content:read'],
    denormalizationContext: ['groups' => 'course:content:write'],
)]
#[ApiResource(
    uriTemplate: '/courses/{id}/course_contents',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            fromProperty: 'courseContents',
            fromClass: Course::class,
        ),
    ],
    normalizationContext: ['groups' => 'course:content:read'],
    denormalizationContext: ['groups' => 'course:content:write'],
)]
class CourseContent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['course:content:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'courseContents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['course:content:read'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['course:content:read'])]
    private ?\DateTimeInterface $timeStart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['course:content:read'])]
    private ?\DateTimeInterface $timeEnd = null;

    #[ORM\OneToMany(mappedBy: 'progress', targetEntity: UserProgresses::class)]
    private Collection $userProgresses;

    public function __construct()
    {
        $this->userProgresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

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

    public function getTimeStart(): ?\DateTimeInterface
    {
        return $this->timeStart;
    }

    public function setTimeStart(?\DateTimeInterface $timeStart): self
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    public function getTimeEnd(): ?\DateTimeInterface
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(?\DateTimeInterface $timeEnd): self
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    /**
     * @return Collection<int, UserProgresses>
     */
    public function getUserProgresses(): Collection
    {
        return $this->userProgresses;
    }

    public function addUserProgress(UserProgresses $userProgress): self
    {
        if (!$this->userProgresses->contains($userProgress)) {
            $this->userProgresses->add($userProgress);
            $userProgress->setProgresse($this);
        }

        return $this;
    }

    public function removeUserProgress(UserProgresses $userProgress): self
    {
        if ($this->userProgresses->removeElement($userProgress)) {
            // set the owning side to null (unless already changed)
            if ($userProgress->getProgresse() === $this) {
                $userProgress->setProgresse(null);
            }
        }

        return $this;
    }
}
