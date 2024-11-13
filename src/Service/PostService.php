<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Enum\ApprovalStatusEnum;
use App\Event\PostStatusUpdatedEvent;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PostService
{
    public function __construct(
        private PostRepository $repo,
        private PaginatorInterface $paginator,
        private EventDispatcherInterface $dispatcher

        )
    {
    }

    public function getQualifiedPostsWithPagonation(int $page, $filters = []) {
        $query = $this->repo
            ->getPublishedAndApprovedPostsQuery((array) $filters)
            ->getQuery();

            return $this->paginator->paginate(
            $query,
            $page,
            8 
        );  
    }

    public function getPostsWithPagonation(int $page) {
        $query = $this->repo->createQueryBuilder('p')
            ->getQuery();
        
        return $this->paginator->paginate(
            $query,
            $page,
            8 
        );  
    }


    public function getNLastPublishedPosts(int $n = 4)
    {
        return $this->repo
        ->createQueryBuilder('p')
        ->setMaxResults($n)
        ->getQuery()
        ->getResult();
    }

    public function store(Post $post, User $user, $isStatusChanged = false) {
        $post->setStatus(ApprovalStatusEnum::PENDING);
         
        $this->repo->save($post);
        
        if($isStatusChanged) {
            $this->dispatcher->dispatch((new PostStatusUpdatedEvent($post, $user)));     
        }
    }

    public function getPostById(string $id): ?Post {
        return $this->repo->find($id);
    }

    public function delete(string $id) {
        $post = $this->getPostById($id);
        $this->repo->remove($post);
    }
}
