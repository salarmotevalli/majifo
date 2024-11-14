<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class ApprovalVoter extends Voter
{
    public const CHANGE = 'CHANGE_POST_STATUS';
    public const READ = 'READ_POST_STATUS';


    protected function supports(string $attribute, mixed $subject): bool
    {
        if  (! in_array($attribute, [self::READ, self::CHANGE])) {
            return  false; 
        }


        // TODO: refactor + comment
        return $subject ? 
             $subject instanceof Post : true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->isUserSuperAdmin($user)) {
            return true;
        }

        $allowed = [
            RoleEnum::POST_MANAGER_ADMIN->value,
        ];
                
        return $this->containAtLeastOneAllowedRole($allowed, $user->getRoles())
            && $subject->getAuthor() != $user;
    }
}
