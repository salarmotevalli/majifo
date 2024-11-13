<?php

namespace App\Controller\Admin;

use App\DTO\Post\PostCreateDto;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Service\FileUploadService;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Ulid;

#[IsGranted('VIEW_PANEL')]
class PostController extends AbstractController
{
    public function __construct(
        private PostService $service,
        private FileUploadService $fileService
    )
    {}

    #[IsGranted('POST_READ')]
    #[Route(path:"admin/post", name:"admin.post.index")]
    public function index(Request $request) {
        return $this->render('admin/page/post/index.html.twig', [
            'items' => $this->service->getPostsWithPagonation($request->query->get('page',1)),
        ]);
    }

    #[IsGranted('POST_WRITE')]
    #[Route(path:"admin/post/new", name:"admin.post.new") ]
    public function new(
        Request $request,
        #[CurrentUser] User $user
        ) {
        $post =  new Post();
        $post->setAuthor($user);

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
           
            if ($uploadedFile) {
                $fileName = $this->fileService->handleUploadedFile($uploadedFile);
                $post->setImageFilename($fileName);
            }

            $this->service->store($post, $user);
            return $this->redirectToRoute('admin.post.index');
        }

        return $this->render('admin/page/post/form.html.twig', [
            'form' => $form
        ]);
    }

    #[IsGranted(attribute: 'POST_READ')]
    #[Route(path:'admin/post/{id}', name:"admin.post.show", methods: 'GET')]
    public function show(Request $request, string $id) {
        $post = $this->service->getPostById($id);

        if (! $post) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(PostType::class, $post, ['disabled' => true]);
        
        return $this->render("admin/page/post/form.html.twig", [
            'form' => $form
        ]);
    }

    #[IsGranted('POST_WRITE')]
    #[Route(path:"admin/post/{id}/edit", name:"admin.post.update", methods: ['GET', 'PUT'])]
    public function update(
        Request $request,
        string $id,
        #[CurrentUser] User $user
        ) {
        $post = $this->service->getPostById($id);
        $postStatus = $post->getStatus();

        if (! $post) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(PostType::class, $post, ['method' => 'PUT']);
        
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {
            $isStatusChanged = $post->getStatus() != $postStatus;

            $uploadedFile = $form['imageFile']->getData();
           
            if ($uploadedFile) {
                $fileName = $this->fileService->handleUploadedFile($uploadedFile);
                $post->setImageFilename($fileName);
            }

            $this->service->store($post, $user, $isStatusChanged);
            
            return $this->redirectToRoute('admin.post.index');
        }

        return $this->render("admin/page/post/form.html.twig", [
            'form' => $form
        ]);
    }

    #[IsGranted('POST_WRITE')]
    #[Route(path:"admin/post/{id}", name:"admin.post.delete", methods: 'DELETE')]
    public function delete(string $id) {
        $post = $this->service->getPostById($id);

        if (! $post) {
            throw new NotFoundHttpException();
        }
        
        $this->service->delete($post);

        return $this->redirectToRoute('admin.post.index');
    }

    #[IsGranted('POST_WRITE')]
    #[Route(path: 'api/admin/post', name: 'api.admin.post.store', methods: 'POST')]
    public function createPost(
        #[MapRequestPayload] PostCreateDto $dto,
        #[CurrentUser] User $user
    ) {
        /** @var Post */
        $post = $dto->toEntity();
        $post->setAuthor($user);
        $this->service->store($post, $user);
    }
}