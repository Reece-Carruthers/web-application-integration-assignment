<?php


namespace App\EndpointControllers;

use App\ClientError;
use App\EndpointGateways\AuthGateway;
use App\Token;

/**
 * Entry point class for fetching data about content of the conference
 *
 * @author Reece Carruthers (w19011575)
 */
class AuthController extends Controller
{
    /**
     * @throws ClientError
     */
    public function __construct(private AuthGateway $gateway, ?array $validParameters)
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
        $this->processResourceRequest($method);
    }

    /**
     * @param string $method
     * @return void
     * @throws ClientError
     */
    public function processResourceRequest(string $method): void
    {
        switch ($method) {
            case 'GET':
            case 'POST':
                if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
                    throw new ClientError(401);
                }
                if (empty(trim($_SERVER['PHP_AUTH_USER'])) || empty(trim($_SERVER['PHP_AUTH_PW']))) {
                    throw new ClientError(401);
                }

                $userRecord = $this->gateway->getUser($_SERVER['PHP_AUTH_USER']);

                if (!password_verify($_SERVER['PHP_AUTH_PW'], $userRecord[0]['password'])) {
                    throw new ClientError(401);
                }

                $token = Token::generateJWT($userRecord[0]['id']);

                $data = ["token" => $token];

                $this->setData($data);
                break;
            default:
                throw new ClientError(405);
        }
    }
}