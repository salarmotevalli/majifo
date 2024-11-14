<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authorization\Voter\Voter as ParentVoter;

abstract class Voter extends ParentVoter {
    protected function containAtLeastOneAllowedRole(array $allowed, array $current)
    {
        foreach ($allowed as $role) {
            if (in_array($role, $current)) {
                return true;
            }
        } 

        return false;    
    }

    protected function isUserSuperAdmin(User $user) {
        return $this->containAtLeastOneAllowedRole([RoleEnum::SUPER_ADMIN->value], $user->getRoles());
    }
}