<?php

namespace App\Repository;

use App\Entity\Post;
use App\Enum\ApprovalStatusEnum;
use App\Repository\Scope\PostScope;
use App\Repository\Scope\PostScopeTrait;
use App\Repository\Scope\ScopableTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    use ScopableTrait;
    use PostScopeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getLastPublishedAndApprovedPostsQuery(array $scopes) {
        $qBuilder = $this
        ->createQueryBuilder('post')
        ->addSelect('postType')
        ->addSelect('categories')
        ->leftJoin('post.postType', 'postType')
        ->leftJoin('post.categories', 'categories');


        $this->isPublished($qBuilder, true);
        $this->status($qBuilder, ApprovalStatusEnum::APPROVED);
        $this->applyScopeSafe($qBuilder, $scopes);
        $this->orderByPublishedAt($qBuilder, 'DESC');
        
        return $qBuilder;
    }

    public function getLastNPublishedAndApprovedPosts(int $n)
    {
        return $this->getLastPublishedAndApprovedPostsQuery([])
            ->setMaxResults($n)
            ->getQuery()
            ->useQueryCache(true)
            ->getResult();
    }

    public function save(Post $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Post $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

}
