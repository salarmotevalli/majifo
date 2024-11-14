<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class PostTypeVoter extends Voter
{
    public const WRITE = 'POST_TYPE_WRITE';
    public const READ = 'POST_TYPE_READ';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::WRITE, self::READ]);
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
            self::READ => $this->adminCanReadPostType($user),
            self::WRITE => $this->adminCanWritePostType($user),
            default => false,
        };
    }

    private function adminCanReadPostType(User $user) : bool {
        $allowed = [
            RoleEnum::NORMAL_ADMIN->value,
            RoleEnum::READ_ONLY_ADMIN->value
        ];

        return $this->containAtLeastOneAllowedRole($allowed, $user->getRoles());    
    }

    private function adminCanWritePostType(User $user) : bool {
        $allowed = [
            RoleEnum::NORMAL_ADMIN->value,
        ];

        return $this->containAtLeastOneAllowedRole($allowed, $user->getRoles());
    }
}
