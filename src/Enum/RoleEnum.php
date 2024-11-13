<?php

namespace App\Enum;

use App\Enum\EnumToArrayTrait;

enum RoleEnum: string {

    use EnumToArrayTrait ;

    case SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    case NORMAL_ADMIN = 'ROLE_NORMAL_ADMIN';
    case READ_ONLY_ADMIN = 'ROLE_READ_ONLY_ADMIN';
    case POST_MANAGER_ADMIN = 'ROLE_POST_MANAGER_ADMIN';

    public static function getAdminRoles(): array {
        return [
            self::SUPER_ADMIN->value,
            self::NORMAL_ADMIN->value,
            self::READ_ONLY_ADMIN->value,
            self::POST_MANAGER_ADMIN->value,
        ];
    }
}