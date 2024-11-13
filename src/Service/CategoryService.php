<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\PostTypeRepository;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;

class CategoryService
{
    public function __construct(
        private CategoryRepository $repo,
        private PaginatorInterface $paginator
        )
    {
    }

    public function getCategoriesWithPagonation(int $page) {
        
        $query = $this->repo->createQueryBuilder('pt')->getQuery();
        
        return $this->paginator->paginate(
            $query,
            $page,
            8 
        );    
    }

    public function store(Category $cat) {
        $this->repo->save($cat);
    }

    public function getCategoryById(string $id): ?Category {
        return $this->repo->find($id);
    }
    
    public function delete(Category $cat) {
        $this->repo->remove($cat);
    }

    public function getAll() {
        return $this->repo->createQueryBuilder('c')->getQuery()->getResult();
    }

}
