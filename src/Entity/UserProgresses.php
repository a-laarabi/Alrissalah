<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\EventListeners\UserProgressPersistentListener;
use App\Repository\UserProgressesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserProgressesRepository::class)]
#[ApiResource(
    operations: [
        new Post(),
    ],
    normalizationContext: ['groups' => 'progress:read'],
    denormalizationContext: ['groups' => 'progress:write'],
)]
#[ORM\EntityListeners([UserProgressPersistentListener::class])]
class UserProgresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['progress:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userProgresses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['progress:read', 'progress:write'])]
    private ?CourseContent $progress = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['progress:read'])]
    private ?\DateTimeInterface $progressDate = null;

    #[ORM\ManyToOne(inversedBy: 'userProgresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->progressDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProgress(): ?CourseContent
    {
        return $this->progress;
    }

    public function setProgress(?CourseContent $progress): self
    {
        $this->progress = $progress;

        return $this;
    }

    public function getProgressDate(): ?\DateTimeInterface
    {
        return $this->progressDate;
    }

    public function setProgressDate(\DateTimeInterface $progressDate): self
    {
        $this->progressDate = $progressDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
