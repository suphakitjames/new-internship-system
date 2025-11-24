<?php
// src/Models/Model.php

namespace Models;

use PDO;

class Model
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
