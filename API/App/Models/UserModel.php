<?php

namespace App\Models;

final class UserModel {

    public static function getFields(): array{
        return [
            'username',
            'password',
            'name'
        ];
    }

    public static function getPermissionFields(): array{
        return [
            'permission_id'
        ];
    }


}