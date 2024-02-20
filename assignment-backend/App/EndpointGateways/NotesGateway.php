<?php

namespace App\EndpointGateways;

use App\ClientError;
use App\Database;
use PDO;

/**
 * Handles the communication between the NotesController and users database for performing CRUD operations on notes
 *
 * All methods have the userId passed in as a parameter from sub of JWT token, this is to ensure that the user is only accessing their data
 *
 * @author Reece Carruthers (w19011575)
 */
class NotesGateway extends Gateway
{

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    /**
     * Takes a user's id and a content ID and returns all notes for that specific content
     * @param array $params
     * @return array
     * @throws ClientError
     */
    public function getNotesForContent(array $params): array
    {
        $sql = "SELECT note_id, content_id, note_text, created_at, updated_at FROM notes WHERE user_id = :userId AND content_id = :content";
        $sqlParams[':userId'] = $params['userId'];

        if (!isset($params['contentID']) || !is_numeric($params['contentID'])) {
            throw new ClientError(400);
        }

        $sqlParams[':content'] = $params['contentID'];

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
     * Gets all notes relating to a user
     * @param string $userId
     * @return array
     * @throws ClientError
     */
    public function getNotes(string $userId): array
    {
        $sql = "SELECT note_id, content_id, note_text, created_at, updated_at FROM notes WHERE user_id = :userId";
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
     * Adds a note to a specific piece of content for a user
     * @param array $params
     * @return array
     * @throws ClientError
     */
    public function addNote(array $params): array
    {
        $sql = "INSERT INTO notes (user_id, content_id, note_text) VALUES (:userId, :content, :note) RETURNING note_id, content_id, note_text, created_at, updated_at";
        $sqlParams[':userId'] = $params['userId'];

        if (!isset($params['contentID']) || !is_numeric($params['contentID'])) {
            throw new ClientError(400);
        }

        if (!isset($params['note']) || strlen($params['note']) == 0) {
            throw new ClientError(400);
        }

        $sqlParams[':content'] = $params['contentID'];
        $sqlParams[':note'] = $params['note'];

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
     * Updates a specific note for a user
     * @param array $params
     * @return array
     * @throws ClientError
     */
    public function updateNote(array $params): array
    {
        $sql = "UPDATE notes SET note_text = :note, updated_at = CURRENT_TIMESTAMP WHERE user_id = :userId AND note_id = :noteId RETURNING note_id, content_id, note_text, created_at, updated_at";
        $sqlParams[':userId'] = $params['userId'];


        if (!isset($params['note']) || strlen($params['note']) == 0) {
            throw new ClientError(400);
        }

        if (!isset($params['noteId']) || !is_numeric($params['noteId'])) {
            throw new ClientError(400);
        }

        $sqlParams[':noteId'] = $params['noteId'];
        $sqlParams[':note'] = $params['note'];

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
     * Deletes a specific note for a user
     * @param array $params
     * @return array
     * @throws ClientError
     *
     * @generated
     */
    public function deleteNote(array $params): array
    {
        $sql = "DELETE FROM notes WHERE user_id = :userId AND note_id = :noteId";
        $sqlParams[':userId'] = $params['userId'];

        if (!isset($params['noteId']) || !is_numeric($params['noteId'])) {
            throw new ClientError(400);
        }

        $sqlParams[':noteId'] = $params['noteId'];

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