<?php

namespace App\Controller;

use App\DTO\Post\PostIndexQueryDto;
use App\Service\CategoryService;
use App\Service\PostService;
use App\Service\PostTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;

class PostController extends AbstractController
{
    public function __construct(
        private PostService $service,
        private CategoryService $categoryService,
        private PostTypeService $typeService,
    )
    {
    }

    #[Route('/post', name: 'post', methods: 'GET')]
    public function index(Request $request)
    {   
        $dto = new PostIndexQueryDto($request->query->all());
        
        $posts = $this->service->getQualifiedPostsWithPagonation(
            $dto->getPage(),
            $dto->getFilters()
        );
   
        return $this->render('page/post/posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/header', name: 'post.index.header', methods: 'GET')]
    public function indexHeader(Request $request)
    {   
        $categories = $this->categoryService->getAll();
        $types = $this->typeService->getAll();

        return $this->render('page/post/header.html.twig', [
            'types' => $types,
            'categories' => $categories
        ])->setMaxAge(3600);
    }

}
