<?php

namespace App\EndpointGateways;

use App\ClientError;
use App\Database;
use PDO;

/**
 * Handles the communication between the FavouriteController and users database for handling favourites
 *
 * All methods have the userId passed in as a parameter from sub of JWT token, this is to ensure that the user is only accessing their data
 *
 * @author Reece Carruthers (w19011575)
 */
class FavouriteGateway extends Gateway
{

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    /**
     * Takes a user's id and a content ID and returns all favourites for that specific content
     * @param array $params
     * @return array
     */
    public function isFavourite(array $params): array
    {
        $sql = "SELECT content_id FROM favourites WHERE user_id = :userId AND content_id = :content";
        $sqlParams[':userId'] = $params['userId'];

        if (!isset($params['contentID']) || !is_numeric($params['contentID'])) {
            throw new ClientError(400);
        }

        $sqlParams[':content'] = $params['contentID'];

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($sqlParams);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            $data = ['isFavourite' => false];
            return ['data' => $data];
        } else {
            $data = ['isFavourite' => true];
            return ['data' => $data];
        }
    }

    /**
     * Gets all favourites relating to a user
     * @param string $userId
     * @return array
     * @throws ClientError
     */
    public function getFavourites(string $userId): array
    {
        $sql = "SELECT content_id FROM favourites WHERE user_id = :userId";
        $sqlParams[':userId'] = $userId;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($sqlParams);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            return ['data' => []];
        } else {
            return ['data' => $data];
        }
    }

    /**
     * Adds a favourite to a specific piece of content for a user
     * @param array $params
     * @return array
     * @throws ClientError
     */
    public function addFavourite(array $params): array
    {
        $sql = "INSERT INTO favourites (user_id, content_id) VALUES (:userId, :content) RETURNING content_id";
        $sqlParams[':userId'] = $params['userId'];

        if (!isset($params['contentID']) || !is_numeric($params['contentID'])) {
            throw new ClientError(400);
        }

        $sqlParams[':content'] = $params['contentID'];

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($sqlParams);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            throw new ClientError(500);
        } else {
            http_response_code(201);
            return ['data' => $data];
        }
    }

    /**
     * Deletes a specific favourite for a user
     * @param array $params
     * @return array
     * @throws ClientError
     */
    public function deleteFavourite(array $params): array
    {
        $sql = "DELETE FROM favourites WHERE user_id = :userId AND content_id = :content";
        $sqlParams[':userId'] = $params['userId'];

        if (!isset($params['contentID']) || !is_numeric($params['contentID'])) {
            throw new ClientError(400);
        }

        $sqlParams[':content'] = $params['contentID'];

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($sqlParams);

        if ($stmt->rowCount() == 0) {
            throw new ClientError(404);
        } else {
            http_response_code(204);
            return [];
        }


    }

    /**
     * Checks to see if a userId exists in the account table
     * @param string $userId
     * @return bool
     * @throws ClientError
     */
    public function doesUserExist(string $userId): bool
    {
        $sql = "SELECT id FROM account WHERE id = :userId";
        $sqlParams[':userId'] = $userId;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($sqlParams);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) != 1) {
            throw new ClientError(400);
        } else {
            return true;
        }
    }
}