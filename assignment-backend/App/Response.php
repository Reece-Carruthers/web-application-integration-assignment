<?php

namespace App;

/**
 * A class which handles the outputting of JSON data and outputting of headers
 *
 * @author Reece Carruthers (W19011575)
 */
class Response
{

    public function __construct()
    {
        $this->outputHeaders();
        if (Request::method() == "OPTIONS") {
            exit();
        }
    }

    private function outputHeaders(): void
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS');
        header('Access-Control-Allow-Headers: Authorization');
    }

    public function outputJSON(array $data): void
    {
        if (empty($data)) {
            http_response_code(204);
        }
        echo json_encode($data);
    }
}