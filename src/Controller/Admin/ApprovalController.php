<?php

namespace App\Controller\Admin;

use App\DTO\Post\PostUpdateStatusDto;
use App\Entity\Post;
use App\Entity\User;
use App\Enum\ApprovalStatusEnum;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/admin/')]
class ApprovalController extends AbstractController{
    public function __construct(
        private PostService $service,
        private EventDispatcherInterface $dispatcher
    ) {
        
    }
    #[IsGranted('READ_POST_STATUS')]
    #[Route(path:"approval", name:"admin.approval.index")]
    public function index(Request $request) {
        return $this->render("admin/page/approval/index.html.twig", [
            'items' => $this->service->getPostsWithPagonation(
                $request->query->get('page',1)
            ),
            'statuses' => ApprovalStatusEnum::array()
        ]);
    }

    #[IsGranted('CHANGE_POST_STATUS')]
    #[Route(path:"approval/{id}", name:"admin.approval.change-status", methods: 'PUT')]
    public function changeStatus(
            Post $post,
            #[CurrentUser] User $user,
            #[MapRequestPayload] PostUpdateStatusDto $dto
    ) {
        if ($dto->status != $post->getStatus()) {
            $post->setStatus($dto->status);
            $this->service->storeStatus($post, $user);
        }

        return $this->redirectToRoute('admin.approval.index');
    }
}