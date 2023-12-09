<?php
declare(strict_types=1);

namespace App\Config;

class DB
{
    public static function getConnection(): \mysqli
    {
        return new \mysqli('localhost', 'root', '', 'untitled');
//        return new \mysqli('mysql', 'root', 'root', 'untitled');
    }
}