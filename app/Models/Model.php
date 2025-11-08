<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = \App\Container::db();
    }
}
