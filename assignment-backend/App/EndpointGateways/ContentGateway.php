<?php

namespace App\EndpointGateways;

use App\ClientError;
use App\Database;
use PDO;

/**
 * Handles the communication between the ContentController and ConferenceDatabase
 * @author Reece Carruthers (w19011575)
 */
class ContentGateway extends Gateway
{

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    /**
     * Fetches data about a specific piece of content from conference
     * The SQL for this section was generated using ChatGPT 3.5
     *
     *
     * @param array $params
     * @return array
     * @throws ClientError
     * @generated
     */
    public function getContentByID(array $params): array
    {

        $sql = "SELECT
            c.id AS content_id,
            c.title AS content_title,
            c.abstract AS content_abstract,
            (SELECT t.name FROM type t WHERE t.id = c.type) AS content_type,
            (SELECT a.name FROM award a INNER JOIN content_has_award cha ON a.id = cha.award WHERE cha.content = c.id) AS award_name
        FROM content c WHERE c.id = :content";


        $sqlParams = [];

        if(isset($params['type']) || isset($params['page'])) {
            throw new ClientError(422);
        }

        if (isset($params['contentID'])) {
            if (!empty($params['contentID']) && is_numeric($params['contentID'])) {
                $sqlParams[':content'] = $params['contentID'];
            } else {
                throw new ClientError(422);
            }
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($sqlParams);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            throw new ClientError(404);
        } else {
            return ['data' => $data];
        }
    }

    /**
     * Fetches all possible types that the conference content can be
     *
     * @param array $params
     * @return array
     * @throws ClientError
     */
    public function getTypes(): array
    {

        $sql = "SELECT name FROM type AS content_type";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            return ['data' => []];
        } else {
            return ['data' => $data];
        }
    }

    /**
     * Fetches data about content of the conference
     * Has the possibility of being filtered by a page of 20 results e.g page=1, page=2...
     * Ability to filter on the type of content to return
     *
     * The SQL for this section was generated using ChatGPT 3.5
     *
     * @param array $params
     * @return array
     * @throws ClientError
     * @generated
     */
    public function getContent(array $params): array
    {

        $sql = "SELECT
            c.id AS content_id,
            c.title AS content_title,
            c.abstract AS content_abstract,
            (SELECT t.name FROM type t WHERE t.id = c.type) AS content_type,
            (SELECT a.name FROM award a INNER JOIN content_has_award cha ON a.id = cha.award WHERE cha.content = c.id) AS award_name
        FROM content c";


        $sqlParams = [];

        if(isset($params['contentID'])) {
            throw new ClientError(422);
        }

        if (isset($params['type'])) {
            if (!empty($params['type']) && !is_numeric($params['type'])) {
                $sql .= " WHERE c.type = (SELECT id FROM type WHERE name = :contentType)";
                $sqlParams[':contentType'] = $params['type'];
            } else {
                throw new ClientError(422);
            }
        }

        if (isset($params['page'])) {
            if (!empty($params['page']) && is_numeric($params['page'])) {
                $sql .= " LIMIT :limit OFFSET :offset";
                $limit = 20;
                $offset = ($params['page'] - 1) * $limit;
                $sqlParams[':limit'] = $limit;
                $sqlParams[':offset'] = $offset;
            } else {
                throw new ClientError(422);
            }
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($sqlParams);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            return [
                'data' => [],
                'pagination' => $this->getPagination($params)
            ];
        } else {
            return ['data' => $data, 'pagination' => $this->getPagination($params)];
        }
    }

    /**
     * Counts the rows within content and returns the total number of rows and amount of pages there are
     * Can pass a type to filter the results and get an accurate total and page count
     *
     * @param array $params
     * @return array
     * @throws ClientError
     * @generated the SQL for this section was generated using ChatGPT 3.5
     */
    public function getPagination(array $params): array
    {
        $sql = "SELECT COUNT(*) FROM content";
        $sqlParams = [];
        if (!empty($params['type'])) {
            if (!is_numeric($params['type'])) {
                $sql .= " JOIN type ON content.type = type.id WHERE type.name = :contentType";
                $sqlParams[':contentType'] = $params['type'];
            } else {

                throw new ClientError(422);
            }
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($sqlParams);

        $count = $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
        $pages = ceil($count / 20);

        return [
            'total' => $count,
            'pages' => $pages,
        ];
    }
}