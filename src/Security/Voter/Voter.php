<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter as ParentVoter;

abstract class Voter extends ParentVoter {
    public function containAtLeastOneAllowedRole(array $allowed, array $current)
    {
        foreach ($allowed as $role) {
            if (in_array($role, $current)) {
                return true;
            }
        } 

        return false;    
    }
}