<?php

namespace App\Service;

use App\Entity\PostType;
use App\Repository\PostRepository;
use App\Repository\PostTypeRepository;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;

class PostTypeService
{
    public function __construct(
        private PostTypeRepository $repo,
        private PaginatorInterface $paginator
        )
    {
    }

    public function getPostTypesWithPagonation(int $page) {
        
        $query = $this->repo->createQueryBuilder('pt')->getQuery();
        
        return $this->paginator->paginate(
            $query,
            $page,
            8 
        );    
    }

    public function store(PostType $type) {
        $this->repo->save($type);
    }

    public function getPostTypeById(string $id): ?PostType {
        return $this->repo->find($id);
    }

    public function delete(string $id) {
        $post = $this->getPostTypeById($id);
        $this->repo->remove($post);    
    }

    public function getAll() {
        return $this->repo->createQueryBuilder('t')->getQuery()->getResult();
    }
}
