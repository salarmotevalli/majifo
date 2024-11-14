<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/')]
#[IsGranted('VIEW_PANEL')]
class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryService $service
    ) 
    {   
    }

    #[IsGranted('CATEGORY_READ')]
    #[Route(path:"category", name:"admin.category.index")]
    public function index(Request $request) {
        return $this->render("admin/page/category/index.html.twig", [
            'items' => $this->service->getCategoriesWithPagonation($request->query->get('page',1)),
        ]);
    }

    #[IsGranted('CATEGORY_WRITE')]
    #[Route(path:"category/new", name:"admin.category.new", methods: ['POST', 'GET'])]
    public function new(Request $request) {
        $cat = new Category();

        $form = $this->createForm(CategoryType::class, $cat, ['method' => 'POST']);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->store($cat);
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render("admin/page/category/form.html.twig", [
            'form' => $form
        ]);
    }

    #[IsGranted('CATEGORY_READ')]
    #[Route(path:'category/{id}', name:"admin.category.show", methods: 'GET')]
    public function show(Request $request, string $id) {
        $cat = $this->service->getCategoryById($id);

        if (! $cat) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(CategoryType::class, $cat, ['disabled' => true]);
        
        return $this->render("admin/page/category/form.html.twig", [
            'form' => $form
        ]);
    }
    
    #[IsGranted('CATEGORY_WRITE')]
    #[Route(path:"category/{id}/edit", name:"admin.category.update", methods: ['GET', 'PUT'])]
    public function update(Request $request, string $id) {
        $cat = $this->service->getCategoryById($id);

        if (! $cat) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(CategoryType::class, $cat, ['method' => 'PUT']);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->store($cat);
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render("admin/page/category/form.html.twig", [
            'form' => $form
        ]);
    }

    #[IsGranted('CATEGORY_WRITE')]
    #[Route(path:"category/{id}", name:"admin.category.delete", methods: 'DELETE')]
    public function delete(Request $request, string $id) {
        $cat = $this->service->getCategoryById($id);

        if (! $cat) {
            throw new NotFoundHttpException();
        }

        $this->service->delete($cat);

        return $this->redirectToRoute('admin.category.index');
    }
}