<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class AdminPanelVoter extends Voter
{
    public const VIEW_PANEL = 'VIEW_PANEL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW_PANEL]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::VIEW_PANEL => $this->userHaveAtLeastOneAdminRole($user),
            default => false,
        };
    }

    private function userHaveAtLeastOneAdminRole(User $user) : bool {
        return $this->containAtLeastOneAllowedRole(
            RoleEnum::getAdminRoles(),
            $user->getRoles()
        );
    }
}
