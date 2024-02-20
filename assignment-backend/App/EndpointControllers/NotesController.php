<?php


namespace App\EndpointControllers;

use App\ClientError;
use App\EndpointGateways\NotesGateway;
use App\Token;

/**
 * Entry point class for fetching data about notes, adding notes, and deleting notes about content
 *
 * @author Reece Carruthers (w19011575)
 */
class NotesController extends Controller
{
    /**
     * @throws ClientError
     */
    public function __construct(private NotesGateway $gateway, ?array $validParameters)
    {
        parent::__construct($validParameters);
    }

    /**
     * @param string $method
     * @param array $params
     * @return void
     * @throws ClientError
     */
    public function processRequest(string $method, array $params): void
    {
        $userId = Token::validateToken();

        $this->gateway->doesUserExist($userId);

        $params['userId'] = $userId;

        if (isset($params['contentID']) || isset($params['noteId']) || $method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
            $this->processResourceRequest($method, $params);
        } else {
            $this->processCollectionRequest($method, $userId);
        }
    }

    /**
     * @param string $method
     * @param array $params
     * @return void
     * @throws ClientError
     */
    public function processResourceRequest(string $method, array $params): void
    {
        switch ($method) {
            case 'GET':
                $this->setData($this->gateway->getNotesForContent($params));
                break;
            case 'POST':
                $this->setData($this->gateway->addNote($params));
                break;
            case 'PUT':
                $this->setData($this->gateway->updateNote($params));
                break;
            case 'DELETE':
                $this->setData($this->gateway->deleteNote($params));
                break;
            default:
                throw new ClientError(405);
        }
    }

    /**
     * @param string $method
     * @param string $userId
     * @return void
     * @throws ClientError
     */
    public function processCollectionRequest(string $method, string $userId): void
    {
        switch ($method) {
            case 'GET':
                $this->setData($this->gateway->getNotes($userId));
                break;
            default:
                throw new ClientError(405);
        }
    }
}