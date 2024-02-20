<?php

namespace App\EndpointControllers;

use App\ClientError;
use App\EndpointGateways\AuthorAffiliationGateway;

/**
 * Entry point class for fetching data about author affiliations
 *
 * @author Reece Carruthers (w19011575)
 */
class AuthorAffiliationController extends Controller
{
    public function __construct(private AuthorAffiliationGateway $gateway, ?array $validParameters)
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
        $this->processCollectionRequest($method, $params);
    }

    /**
     * @param string $method
     * @param array $params
     * @return void
     * @throws ClientError
     */
    public function processCollectionRequest(string $method, $params): void
    {
        switch ($method) {
            case 'GET':
                $this->setData($this->gateway->getAuthorAffiliations($params));
                break;
            default:
                throw new ClientError(405);
        }
    }
}