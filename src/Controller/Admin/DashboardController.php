<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[IsGranted('VIEW_PANEL')]
    #[Route(path:"admin", name:"admin.dashboard")]
    public function index(#[CurrentUser] User $user) {
        return $this->render("admin/page/dashboard.html.twig");
    }
}