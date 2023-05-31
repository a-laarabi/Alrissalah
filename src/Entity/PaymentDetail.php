<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PaymentDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaymentDetailRepository::class)]
class PaymentDetail
{
    const TYPE_BOOK = 'book';
    const TYPE_LEVEL = 'level';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['payment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['payment:read', 'payment:write'])]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Groups(['payment:read', 'payment:write'])]
    private ?string $data = null;

    #[ORM\OneToOne(mappedBy: 'detail', cascade: ['persist', 'remove'])]
    private ?Payment $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): self
    {
        // set the owning side of the relation if necessary
        if ($payment->getDetail() !== $this) {
            $payment->setDetail($this);
        }

        $this->payment = $payment;

        return $this;
    }
}
