<?php

namespace App\Controller;

use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private PostService $postService)
    {
    }

    #[Route('/', name: 'home')]
    public function home()
    {
        $latestPosts = $this->postService->getLastFiveQualifiedPosts();

        return $this->render('page/home.html.twig', [
            'latestPosts' => $latestPosts
        ]);
    }
}
