<?php

namespace App\Controller\Admin;

use App\Entity\PostType;
use App\Form\PostTypeType;
use App\Service\PostTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostTypeController extends AbstractController
{

    public function __construct(
        private PostTypeService $service
    ) 
    {   
    }

    #[IsGranted('POST_TYPE_READ')]
    #[Route(path:"admin/post-type", name:"admin.post-type.index")]
    public function index(Request $request) {
        return $this->render("admin/page/post-type/index.html.twig", [
            'items' => $this->service->getPostTypesWithPagonation($request->query->get('page',1)),
        ]);
    }

    #[IsGranted('POST_TYPE_WRITE')]
    #[Route(path:"admin/post-type/new", name:"admin.post-type.new")]
    public function new(Request $request) {
        $type = new PostType();
        
        $form = $this->createForm(PostTypeType::class, $type);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->store($type);
            return $this->redirectToRoute('admin.post-type.index');
        }

        return $this->render("admin/page/post-type/form.html.twig", [
            'form' => $form
        ]);
    }

  
    #[IsGranted('POST_TYPE_READ')]
    #[Route(path:'admin/post-type/{id}', name:"admin.post-type.show", methods: 'GET')]
    public function show(Request $request, string $id) {
        $type = $this->service->getPostTypeById($id);

        if (! $type) {
            return $this->createNotFoundException();
        }

        $form = $this->createForm(PostType::class, $type, ['disabled' => true]);
        
        return $this->render("admin/page/post-type/form.html.twig", [
            'form' => $form
        ]);
    }

    #[IsGranted('POST_TYPE_WRITE')]
    #[Route(path:"admin/post-type/{id}/edit", name:"admin.post-type.update", methods: ['GET', 'PUT'])]
    public function update(Request $request, string $id) {
        $type = $this->service->getPostTypeById($id);

        if (! $type) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(PostTypeType::class, $type, ['method' => 'PUT']);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->store($type);
            return $this->redirectToRoute('admin.post-type.index');
        }

        return $this->render("admin/page/post-type/form.html.twig", [
            'form' => $form
        ]);
    }

    #[IsGranted('POST_TYPE_WRITE')]
    #[Route(path:"admin/post-type/{id}", name:"admin.post-type.delete", methods: 'DELETE')]
    public function delete(Request $request, string $id) {
        $type = $this->service->getPostTypeById($id);

        if (! $type) {
            throw new NotFoundHttpException();
        }

        $this->service->delete($type);

        return $this->redirectToRoute('admin.post-type.index');
    }


}