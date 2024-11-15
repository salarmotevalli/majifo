<?php

namespace App\DTO\Post;

use App\DTO\ToEntityInterface;
use App\Entity\Post;
use App\Entity\PostType;
use DateTime;

use Symfony\Component\Validator\Constraints as Assert;


class PostCreateDto implements ToEntityInterface {
    public function __construct(
        
        #[Assert\NotBlank]
        public readonly string $title,
        public readonly string $content,
        public readonly PostType $type,
        public readonly DateTime $publishedAt,
    ) {}   

    public function toEntity() {
        $post = new Post();

        $post->setTitle($this->title);
        $post->setContent($this->content);
        $post->setPostType($this->type);
        $post->setPublishedAt($this->publishedAt);

        return $post;
    }
}