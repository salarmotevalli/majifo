<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/')]
class UserController extends AbstractController
{
    public function __construct(
        private UserService $service
    )
    {}

    #[IsGranted(attribute: 'USER_READ')]
    #[Route(path:"user", name:"admin.user.index")]
    public function index(Request $request) {
        return $this->render('admin/page/user/index.html.twig', [
            'items' => $this->service->getUsersWithPagonation($request->query->get('page',1)),
        ]);
    }

    #[IsGranted(attribute: 'USER_Write')]
    #[Route(path:"user/new", name:"admin.user.new") ]
    public function new(Request $request) {
        $user =  new User();
        $form = $this->createForm(UserType::class, $user);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->store($user);
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/page/user/form.html.twig', [
            'form' => $form
        ]);
    }

    #[IsGranted(attribute: 'POST_READ')]
    #[Route(path:'user/{id}', name:"admin.user.show", methods: 'GET')]
    public function show(Request $request, string $id) {
        $user = $this->service->getUserById($id);

        if (! $user) {
            return new Response('not');
        }

        $form = $this->createForm(UserType::class, $user, ['disabled' => true]);
        
        return $this->render("admin/page/user/form.html.twig", [
            'form' => $form
        ]);
    }

    #[IsGranted(attribute: 'POST_WRITE')]
    #[Route(path:"user/{id}/edit", name:"admin.user.update", methods: ['GET', 'PUT'])]
    public function update(Request $request, string $id) {
        $user = $this->service->getUserById($id);

        if (! $user) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->store($user);
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render("admin/page/user/form.html.twig", [
            'form' => $form
        ]);
    }

    #[IsGranted(attribute: 'USER_WRITE')]
    #[Route(path:"user/{id}", name:"admin.user.delete", methods: 'DELETE')]
    public function delete(Request $request, string $id) {
        $user = $this->service->getUserById($id);

        if (! $user) {
            throw new NotFoundHttpException();
        }

        $this->service->delete($user);

        return $this->redirectToRoute('admin.user.index');
    }
}