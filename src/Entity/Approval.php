<?php

namespace App\Entity;

use App\Enum\ApprovalStatusEnum;
use App\Repository\ApprovalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApprovalRepository::class)]
class Approval
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $whenDate = null;

    #[ORM\Column(enumType: ApprovalStatusEnum::class)]
    private ?ApprovalStatusEnum $changedTo = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $by = null;

    #[ORM\ManyToOne(inversedBy: 'approvals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWhenDate(): ?\DateTimeImmutable
    {
        return $this->whenDate;
    }

    public function setWhenDate(\DateTimeImmutable $whenDate): static
    {
        $this->whenDate = $whenDate;

        return $this;
    }

    public function getChangedTo(): ?ApprovalStatusEnum
    {
        return $this->changedTo;
    }

    public function setChangedTo(ApprovalStatusEnum $changedTo): static
    {
        $this->changedTo = $changedTo;

        return $this;
    }

    public function getBy(): ?User
    {
        return $this->by;
    }

    public function setBy(?User $by): static
    {
        $this->by = $by;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

}
