<?php

namespace App\DTO\Post;

use App\DTO\ToEntityInterface;
use App\Entity\Post;
use App\Entity\PostType;
use App\Enum\ApprovalStatusEnum;
use DateTime;

use Symfony\Component\Validator\Constraints as Assert;


class PostUpdateStatusDto {
    public function __construct(
        public readonly ApprovalStatusEnum $status,
    ) {}   

}