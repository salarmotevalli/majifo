<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const WRITE = 'USER_WRITE';
    public const READ = 'USER_READ';

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
            self::READ => $this->adminCanReadUser($user),
            self::WRITE => $this->adminCanWriteUser($user),
            default => false,
        };
    }

    private function adminCanReadUser(User $user) : bool {
        $allowed = [
            RoleEnum::SUPER_ADMIN->value,
        ];

        return $this->containAtLeastOneAllowedRole($allowed, $user->getRoles());   
    }

    private function adminCanWriteUser(User $user) : bool {
        $allowed = [
            RoleEnum::SUPER_ADMIN->value
        ];
        
        return $this->containAtLeastOneAllowedRole($allowed, $user->getRoles());
    }

}
