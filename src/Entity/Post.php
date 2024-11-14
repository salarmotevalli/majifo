<?php

namespace App\Entity;

use App\Enum\ApprovalStatusEnum;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\Column(length: 60)]
    private ?string $title = null;

    #[ORM\Column(enumType: ApprovalStatusEnum::class, options: ["default" => ApprovalStatusEnum::PENDING])]
    private ?ApprovalStatusEnum $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $publishedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostType $postType = null;
    
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;
    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'posts', fetch: "EXTRA_LAZY")]
    private Collection $categories;

    /**
     * @var Collection<int, Approval>
     */
    #[ORM\OneToMany(targetEntity: Approval::class, mappedBy: 'post')]
    private Collection $approvals;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFilename = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->approvals = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getPostType(): ?PostType
    {
        return $this->postType;
    }

    public function setPostType(?PostType $postType): static
    {
        $this->postType = $postType;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addPost($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removePost($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Approval>
     */
    public function getApprovals(): Collection
    {
        return $this->approvals;
    }

    public function addApproval(Approval $approval): static
    {
        if (!$this->approvals->contains($approval)) {
            $this->approvals->add($approval);
            $approval->setPost($this);
        }

        return $this;
    }

    public function removeApproval(Approval $approval): static
    {
        if ($this->approvals->removeElement($approval)) {
            // set the owning side to null (unless already changed)
            if ($approval->getPost() === $this) {
                $approval->setPost(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?ApprovalStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ApprovalStatusEnum $staus): static
    {
        $this->status = $staus;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): static
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }
}
