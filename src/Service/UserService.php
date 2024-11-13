<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;

class UserService
{
    public function __construct(
        private UserRepository $repo,
        private PaginatorInterface $paginator
        )
    {
    }

    public function getUsersWithPagonation(int $page) {
        $query = $this->repo->createQueryBuilder('pt')->getQuery();
        
        return $this->paginator->paginate(
            $query,
            $page,
            8 
        );    
    }

    public function store(User $user) {
        $this->repo->save($user);    
    }

    public function getUserById(string $id): ?User {
        return $this->repo->find($id);
    }
    
    public function delete(string $id) {
        $user = $this->getUserById($id);
        $this->repo->remove($user);
    }
}
