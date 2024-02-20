<?php

namespace App\EndpointControllers;

use App\ClientError;
use App\Request;

class Controller
{
    private array $data;

    /**
     * @throws ClientError
     */
    public function __construct(?array $validParameters)
    {
        if (!Request::isRequestValid($validParameters)) {
            throw new ClientError(422);
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($value)
    {
        $this->data = $value;
    }
}