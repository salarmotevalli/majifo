<?php

namespace App\Service;

use App\Entity\Approval;
use App\Repository\ApprovalRepository;

class ApprovalService {
    public function __construct(
        private ApprovalRepository $repo,
    ) 
    {}

    public function store(Approval $approval)
    {
        $em = $this->repo->getEntityManager();
        $em->persist($approval);
        $em->flush();
    }
}