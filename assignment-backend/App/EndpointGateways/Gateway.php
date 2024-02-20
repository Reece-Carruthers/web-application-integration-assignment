<?php

namespace App\EndpointGateways;

use App\Database;
use PDO;

/**
 * Parent class for all Gateway classes
 * @author Reece Carruthers (W19011575)
 */
class Gateway
{
    protected PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
}