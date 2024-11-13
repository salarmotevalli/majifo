<?php

namespace App\Repository\Scope;

use App\Enum\ApprovalStatusEnum;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

trait PostScopeTrait {
    public function isPublished(QueryBuilder $builder, bool $isPublished = true) {
        $operator = $isPublished ? '<=' :'>=';
        
        // $builder->where('post.publishedAt '.$operator.' :now');
        $builder->andWhere('post.publishedAt '.$operator.' :now')
            ->setParameter('now', 'NOW()');
    }

    public function status(QueryBuilder $builder, $status) {
        // $builder->where('post.publishedAt '.$operator.' :now');
        $builder->andWhere('post.status = :status')
            ->setParameter('status', $status);
    }

    public function postType(QueryBuilder $builder, string $type_id) {
        $builder->andWhere('post.postType = :id')
        ->setParameter('id', $type_id, UlidType::NAME);
    }

    public function orderByPublishedAt(QueryBuilder $builder, $order = 'ASC') {
        $builder->orderBy('post.publishedAt', $order);
    }

    public function categories(QueryBuilder $builder, array $categories) {
        // TODO: refactore
        // convert ulids to uuids
        $cats = array_map(fn($cat) => Ulid::fromString($cat)->toRfc4122(), $categories); 
        
        $builder->join('post.categories', 'cp')
        ->where('cp.id IN (:categories)')
        ->setParameter('categories', $cats);
    }
}