<?php

namespace App\EndpointGateways;

use App\ClientError;
use App\Database;
use App\Request;
use PDO;

/**
 * Handles the communication between the PreviewController and ConferenceDatabase
 * @author Reece Carruthers (w19011575)
 */
class PreviewGateway extends Gateway
{

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    /**
     * Fetches random rows of data which contain a title of the talk and a preview video url for said talk
     * If limit is passed as a parameter a specific number of rows will be returned
     *
     * @param array $params
     * @return array
     * @throws ClientError
     */
    public function getPreviews(array $params): array
    {

        $sql = "SELECT id AS content_id, title, preview_video FROM content WHERE preview_video IS NOT NULL ORDER BY RANDOM()";

        $filter = "";
        $sqlParams = [];

        if (isset($params['limit'])) {
            if (!is_numeric($params['limit'])) {
                throw new ClientError(422);
            }

            $filter = "LIMIT :limit";
            $sqlParams['limit'] = Request::params()['limit'];
        }

        $queryWithFilter = "$sql $filter";

        $stmt = $this->conn->prepare($queryWithFilter);
        $stmt->execute($sqlParams);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['data' => $data];
    }
}