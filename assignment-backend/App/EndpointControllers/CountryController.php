<?php

namespace App\EndpointControllers;

use App\ClientError;
use App\EndpointGateways\CountryGateway;

/**
 * Entry point class for fetching data about conference content
 *
 * @author Reece Carruthers (w19011575)
 */
class CountryController extends Controller
{
    public function __construct(private CountryGateway $gateway, ?array $validParameters)
    {
        parent::__construct($validParameters);
    }

    /**
     * @param string $method
     * @return void
     * @throws ClientError
     */
    public function processRequest(string $method): void
    {
        $this->processCollectionRequest($method);
    }

    /**
     * @param string $method
     * @return void
     * @throws ClientError
     */
    public function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case 'GET':
                $this->setData($this->gateway->getCountries());
                break;
            default:
                throw new ClientError(405);
        }
    }

}