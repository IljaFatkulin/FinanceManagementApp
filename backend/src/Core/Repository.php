<?php
declare(strict_types=1);

namespace App\Core;

use App\Config\DB;

abstract class Repository
{
    protected \mysqli $connection;

    public function __construct()
    {
        $this->connection = DB::getConnection();
    }
}