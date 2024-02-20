<?php


namespace App\EndpointControllers;

use App\ClientError;
use App\EndpointGateways\FavouriteGateway;
use App\Token;

/**
 * Entry point class for fetching data about favourites, adding favourites, and deleting favourites
 *
 * @author Reece Carruthers (w19011575)
 */
class FavouriteController extends Controller
{
    /**
     * @throws ClientError
     */
    public function __construct(private FavouriteGateway $gateway, ?array $validParameters)
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

        if (isset($params['contentID'])) {
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
                $this->setData($this->gateway->isFavourite($params));
                break;
            case 'POST':
                $this->setData($this->gateway->addFavourite($params));
                break;
            case 'DELETE':
                $this->setData($this->gateway->deleteFavourite($params));
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
                $this->setData($this->gateway->getFavourites($userId));
                break;
            default:
                throw new ClientError(405);
        }
    }
}