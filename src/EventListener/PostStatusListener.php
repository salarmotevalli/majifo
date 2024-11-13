<?php

namespace App\EventListener;

use App\Entity\Approval;
use App\Event\PostStatusUpdatedEvent;
use App\Service\ApprovalService;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class PostStatusListener
{
    public function __construct(
        private ApprovalService $servicve
    ) {}

    #[AsEventListener(event: PostStatusUpdatedEvent::class)]
    public function onPostStatusUpdatedEvent(PostStatusUpdatedEvent $event): void
    {
        $approval = new Approval();
        
        $approval->setChangedTo($event->post->getStatus());
        $approval->setBy($event->user);
        $approval->setPost($event->post);
        $approval->setWhenDate(new DateTimeImmutable('now'));

        $this->servicve->store($approval);
    }
}
