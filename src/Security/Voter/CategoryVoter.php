<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class CategoryVoter extends Voter
{
    public const WRITE = 'CATEGORY_WRITE';
    public const READ = 'CATEGORY_READ';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::WRITE, self::READ]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        return match ($attribute) {
            self::READ => $this->adminCanReadCategory($user),
            self::WRITE => $this->adminCanWriteCategory($user),
            default => false,
        };
    }

    private function adminCanReadCategory(User $user) : bool {
        $allowed = [
            RoleEnum::NORMAL_ADMIN->value,
            RoleEnum::SUPER_ADMIN->value,
            RoleEnum::READ_ONLY_ADMIN->value
        ];
        
        return $this->containAtLeastOneAllowedRole($allowed, $user->getRoles());
    }

    private function adminCanWriteCategory(User $user) : bool {
        $allowed = [
            RoleEnum::NORMAL_ADMIN->value,
            RoleEnum::SUPER_ADMIN->value
        ];

        return $this->containAtLeastOneAllowedRole($allowed, $user->getRoles());
    }
}
