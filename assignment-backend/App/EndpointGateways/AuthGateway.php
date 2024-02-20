<?php

namespace App\EndpointGateways;

use App\ClientError;
use App\Database;
use PDO;

/**
 * Handles the communication between the AuthController and users database
 *
 * @author Reece Carruthers (w19011575)
 */
class AuthGateway extends Gateway
{

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    /**
     * Takes a user's email and returns their record from the database or an error if the user doesn't exist
     * @param string $params
     * @return array
     * @throws ClientError
     */
    public function getUser(string $params): array
    {
        $sql = "SELECT id, password FROM account WHERE email = :email";
        $sqlParams[':email'] = $params;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($sqlParams);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) != 1) {
            throw new ClientError(401);
        } else {
            return $data;
        }

    }


}