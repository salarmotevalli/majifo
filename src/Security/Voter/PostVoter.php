<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class PostVoter extends Voter
{
    public const READ = 'POST_READ';
    public const WRITE = 'POST_WRITE';

    protected function supports(string $attribute, mixed $subject): bool
    {

        if  (! in_array($attribute, [self::READ, self::WRITE])) 
        { return  false; }


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

        return match ($attribute) {
            self::READ => $this->adminCanReadPost($user),
            self::WRITE => $this->adminCanWritePost($user, $subject),
            default => false,
        };
    }

    private function adminCanReadPost(User $user) : bool {
        $allowed = [
            RoleEnum::NORMAL_ADMIN->value,
            RoleEnum::POST_MANAGER_ADMIN->value,
            RoleEnum::READ_ONLY_ADMIN->value
        ];

        return $this->containAtLeastOneAllowedRole($allowed, $user->getRoles());   
    }

    private function adminCanWritePost(User $user, $post) : bool {
        $allowed = [
            RoleEnum::NORMAL_ADMIN->value,
            RoleEnum::POST_MANAGER_ADMIN->value,
        ];
        
        return $this->containAtLeastOneAllowedRole($allowed, $user->getRoles())
                && $this->isOwner($user, $post);
    }

    private function isOwner(User $user, ?Post $post) {
        return $post 
            ? $post->getAuthor() === $user
            : true;
    }
}
