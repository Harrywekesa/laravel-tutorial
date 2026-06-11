<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Member = 'member';

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }
}
