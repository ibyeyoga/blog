<?php

namespace App\Enum;

enum Role: int
{
    case ADMIN = 1;
    case USER = 0;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => '超级管理员',
            self::USER => '用户',
        };
    }
}