<?php

namespace App\Controller;

use App\DTO\Post\PostIndexDTO;
use App\DTO\Post\PostIndexQueryDto;
use App\Service\CategoryService;
use App\Service\PostService;
use App\Service\PostTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Ulid;

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
        
        
        $categories = $this->categoryService->getAll();
        $types = $this->typeService->getAll();

        return $this->render('page/posts.html.twig', [
            'posts' => $posts,
            'types' => $types,
            'categories' => $categories
        ]);
    }
}
