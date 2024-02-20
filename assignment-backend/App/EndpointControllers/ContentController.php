<?php


namespace App\EndpointControllers;

use App\ClientError;
use App\EndpointGateways\ContentGateway;

/**
 * Entry point class for fetching data about content of the conference
 *
 * @author Reece Carruthers (w19011575)
 */
class ContentController extends Controller
{
    /**
     * @throws ClientError
     */
    public function __construct(private ContentGateway $gateway, ?array $validParameters)
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
        if (isset($params['contentID'])) {
            $this->processResourceRequest($method, $params);
        } else {
            $this->processCollectionRequest($method, $params);
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
                $this->setData($this->gateway->getContentByID($params));
                break;
            default:
                throw new ClientError(405);
        }
    }

    /**
     * @param string $method
     * @param array $params
     * @return void
     * @throws ClientError
     */
    public function processCollectionRequest(string $method, array $params): void
    {
        switch ($method) {
            case 'GET':
                if(isset($params['type']) && $params['type'] === 'listTypes') {
                    $this->setData($this->gateway->getTypes());
                }else {
                    $this->setData($this->gateway->getContent($params));
                }
                break;
            default:
                throw new ClientError(405);
        }
    }
}