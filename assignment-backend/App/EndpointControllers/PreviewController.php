<?php


namespace App\EndpointControllers;

use App\ClientError;
use App\EndpointGateways\PreviewGateway;

/**
 * Entry point class for fetching data about previews
 *
 * @author Reece Carruthers (w19011575)
 */
class PreviewController extends Controller
{
    /**
     * @throws ClientError
     */
    public function __construct(private PreviewGateway $gateway, ?array $validParameters)
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
    public function processCollectionRequest(string $method, array $params): void
    {
        switch ($method) {
            case 'GET':
                $this->setData($this->gateway->getPreviews($params));
                break;
            default:
                throw new ClientError(405);
        }
    }
}