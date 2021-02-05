<?php

namespace App\Models;

final class RoomModel {

    public static function getFields(): array{
        return [
            'user_id',
            'name',
        ];
    }


}