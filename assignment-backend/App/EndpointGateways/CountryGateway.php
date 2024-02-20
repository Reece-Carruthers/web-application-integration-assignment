<?php

namespace App\EndpointGateways;

use App\Database;
use PDO;

/**
 * Handles the communication between the CountryController and ConferenceDatabase
 * @author Reece Carruthers (W19011575)
 */
class CountryGateway extends Gateway
{

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    /**
     * Fetches all countries from the database
     *
     * Returns the countries as one array since they do not have a unique identifier
     * @return array
     */
    public function getCountries(): array
    {
        $sql = "SELECT DISTINCT country FROM affiliation";

        $stmt = $this->conn->query($sql);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['data' => $data];
    }
}