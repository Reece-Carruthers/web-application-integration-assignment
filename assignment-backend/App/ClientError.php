<?php

namespace App;

use Exception;

/**
 * Custom error handler which calls on the logger when an error happens
 *
 * Inspiration taken from the module lectures
 *
 * @author KF6012 and Reece Carruthers (w19011575)
 */
class ClientError extends Exception
{

    /**
     * @param int $code
     * @throws Exception
     */
    public function __construct(int $code)
    {
        parent::__construct($this->errorResponses($code));
    }

    /**
     * @throws Exception
     * @param int $code
     * @return string
     */
    public function errorResponses($code): string
    {
        switch ($code) {
            case 400:
                $message = 'Bad Request';
                http_response_code(400);
                break;
            case 401:
                $message = 'Not Authorized';
                http_response_code(401);
                break;
            case 404:
                $message = 'Resource Not Found';
                http_response_code(404);
                break;
            case 405:
                $message = 'Method Not Allowed';
                http_response_code(405);
                break;
            case 422:
                $message = 'Unprocessable Entity';
                http_response_code(422);
                break;
            default:
                throw new \Exception('Internal Server Error');
        }
        Logger::logMessage($message);
        return $message;
    }
}