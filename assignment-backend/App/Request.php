<?php

namespace App;
/**
 * Helper class which holds and fetches useful information about incoming requests
 * Has some helper functions which help checking request parameters
 *
 * @author Reece Carruthers (w19011575)
 */
abstract class Request
{

    /**
     * Returns the endpoint name from the request URI
     * @return string
     */
    public static function endpointName(): string
    {
        $url = $_SERVER['REQUEST_URI'];
        $path = parse_url($url)['path'];

        return strtolower(str_replace(BASEPATH, "", $path));
    }

    /**
     * A function which takes a associative array which contains a method mapped to an array of allowed parameters e.g ['GET' => ['limit']]
     * The function then checks if the request method is in the array and if the request parameters are valid
     * @throws ClientError
     */
    public static function isRequestValid(array $validParameters): bool
    {
        $method = Request::method();

        if (!array_key_exists($method, $validParameters)) {
            throw new ClientError(405);
        }

        return Request::areParametersValid($validParameters[$method]);
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Removes valid parameters from the params array and then performs a check to see if the value is zero
     * This indicates that all parameters with the request are correct
     * @param array|null $validParameters
     * @return bool
     */
    public static function areParametersValid(?array $validParameters): bool
    {
        if (is_null($validParameters)) {
            return true;
        }
        return count(array_diff_key(Request::params(), array_flip($validParameters))) === 0;
    }

    public static function params(): array
    {
        return $_REQUEST;
    }

    /**
     * Returns the authorization header from the request
     * @param array $allHeaders
     * @return string
     */
    public static function getAuthorizationHeaders(array $allHeaders): string
    {
        $authorizationHeader = "";

        if (array_key_exists('Authorization', $allHeaders)) {
            $authorizationHeader = $allHeaders['Authorization'];
        } elseif (array_key_exists('authorization', $allHeaders)) {
            $authorizationHeader = $allHeaders['authorization'];
        }
        return $authorizationHeader;
    }
}