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

    public function getPublishedAndApprovedPostsQuery(array $scopes) {
        $qBuilder = $this
        ->createQueryBuilder('post');

        $scopes['isPublished'] = true;
        $scopes['status'] = ApprovalStatusEnum::APPROVED;

        $this->applyScope($qBuilder, $scopes);
        
        return $qBuilder;
    }
}