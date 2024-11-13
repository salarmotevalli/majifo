<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Enum\ApprovalStatusEnum;
use App\Event\PostStatusUpdatedEvent;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PostService
{
    public function __construct(
        private PostRepository $repo,
        private PaginatorInterface $paginator,
        private EventDispatcherInterface $dispatcher,
        private CacheInterface $cache
        )
    {
    }

    public function getQualifiedPostsWithPagonation(int $page, $filters = []) {
        $query = $this->repo
            ->getLastPublishedAndApprovedPostsQuery((array) $filters)->getQuery();

        return $this->paginator
            ->paginate($query,$page,8);  
    }

    public function getPostsWithPagonation(int $page) {
        $query = $this->repo->createQueryBuilder('p')
            ->getQuery();
        
        return $this->paginator
            ->paginate($query, $page, 8 );  
    }


    public function getLastFiveQualifiedPosts()
    {
        return $this->cache
            ->get('last-five-qualified-posts', function (ItemInterface $item) {
                $item->expiresAfter(3600);

                return $this->repo->getLastNPublishedAndApprovedPosts(5);
            }
        );
        
        
        // repo

        // ->createQueryBuilder('p')
        // ->setMaxResults(4)
        // ->getQuery()
        // ->getResult();
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

    public function delete(Post $post) {
        $this->repo->remove($post);
    }
}
