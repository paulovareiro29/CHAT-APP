<?php

namespace App\Models;

final class MessageModel {

    public static function getFields(): array{
        return [
            'user_id',
            'content'
        ];
    }


}