<?php

namespace App\Enum;

enum Operation: int
{
    case LOGIN = 0;
    case LOGOUT = 1;
    case CREATE_POST = 2;
    case UPDATE_POST = 3;
    case DELETE_POST = 4;

    public function label(): string
    {
        return match ($this) {
            self::LOGIN => '登录',
            self::LOGOUT => '登出',
            self::CREATE_POST => '创建文章',
            self::UPDATE_POST => '更新文章',
            self::DELETE_POST => '删除文章',
        };
    }
}