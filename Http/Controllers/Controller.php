<?php

namespace Http\Controllers;

use Core\Container;
use Core\Database;


class Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Container::getInstance()->resolve(Database::class);
    }
}
