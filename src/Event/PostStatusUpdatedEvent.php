<?php

namespace App\Event;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class PostStatusUpdatedEvent extends Event {
    public function __construct(
        public readonly Post $post,
        public readonly User $user
    ) 
    {}
}