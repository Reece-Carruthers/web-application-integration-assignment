<?php

namespace App;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * A class for handling JWT tokens
 *
 * @author Reece Carruthers (W19011575)
 */
abstract class Token
{

    /**
     * Generates a JWT bearer token
     * @param string $id
     * @return string
     */
    public static function generateJWT(string $id): string
    {

        $iat = time();
        $exp = strtotime(BEARER_TOKEN_EXPIRY_TIME, $iat);
        $iss = $_SERVER['HTTP_HOST'];

        $payload = [
            'sub' => $id,
            'iat' => $iat,
            'exp' => $exp,
            'iss' => $iss
        ];

        return JWT::encode($payload, SECRET, 'HS256');
    }

    /**
     * @throws ClientError
     * @return string
     */
    public static function validateToken(): string
    {
        $key = SECRET;
        $allHeaders = getallheaders();

        $authorizationHeader = Request::getAuthorizationHeaders($allHeaders);

        if (substr($authorizationHeader, 0, 7) != 'Bearer ') {
            throw new ClientError(401);
        }

        $jwt = trim(substr($authorizationHeader, 7));

        try {
            $decodedJWT = JWT::decode($jwt, new Key($key, 'HS256'));
        } catch (Exception) {
            throw new ClientError(401);
        }

        if (!isset($decodedJWT->sub)) {
            throw new ClientError(401);
        }

        return $decodedJWT->sub;
    }
}