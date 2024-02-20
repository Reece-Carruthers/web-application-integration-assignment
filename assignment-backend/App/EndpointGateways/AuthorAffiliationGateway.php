<?php


namespace App\EndpointGateways;

use App\ClientError;
use App\Database;
use PDO;

/**
 * Handles the communication between the AuthorAffiliationController and ConferenceDatabase
 *
 * @author Reece Carruthers (w19011575)
 */
class AuthorAffiliationGateway extends Gateway
{

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    /**
     * Fetches data about author's affiliations to publications
     * Has the possibility of being filtered by a country or a content ID.
     *
     * The SQL for this section was generated using ChatGPT 3.5
     *
     * @throws ClientError
     * @generated
     */
    public function getAuthorAffiliations(array $params): array
    {

        $sql = "SELECT
                a.id AS author_id,
                a.name AS author_name,
                c.id AS content_id,
                c.title AS content_title,
                (SELECT af.country FROM affiliation af WHERE af.author = a.id AND af.content = c.id) AS affiliation_country,
                (SELECT af.city FROM affiliation af WHERE af.author = a.id AND af.content = c.id) AS affiliation_city,
                (SELECT af.institution FROM affiliation af WHERE af.author = a.id AND af.content = c.id) AS affiliation_institution
                FROM author AS a
                JOIN content_has_author AS cha ON a.id = cha.author
                JOIN content AS c ON cha.content = c.id";

        $filter = "";
        $sqlParams = [];

        if (isset($params['country']) && isset($params['contentID'])) {
            throw new ClientError(422);
        } else {

            if (isset($params['country'])) {
                if (!empty($params['country']) && !is_numeric($params['country'])) {
                    $sql .= " WHERE (SELECT af.country FROM affiliation af WHERE af.author = a.id AND af.content = c.id) = :country";
                    $sqlParams[':country'] = $params['country'];
                } else {
                    throw new ClientError(422);
                }
            }

            if (isset($params['contentID'])) {
                if (!empty($params['contentID']) && is_numeric($params['contentID'])) {
                    $sql .= " WHERE c.id = :content_id";
                    $sqlParams[':content_id'] = $params['contentID'];
                } else {
                    throw new ClientError(422);
                }
            }
        }

        $queryWithFilter = "$sql $filter";

        $stmt = $this->conn->prepare($queryWithFilter);
        $stmt->execute($sqlParams);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            if(isset($params['contentID'])) {
                throw new ClientError(404);
            } else {
                return ['data' => []];
            }
        } else {
            return ['data' => $data];
        }
    }
}