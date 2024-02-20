<?php

namespace config;
use Throwable;

/**
 * A function which will handle any uncaught exceptions and output information about the exception and set the HTTP status to 500
 * @author Reece Carruthers (w19011575)
 */
function exceptionHandler(Throwable $exception): void
{
    http_response_code(500);
    echo json_encode([
        "code" => $exception->getCode(),
        "message" => $exception->getMessage(),
        "file" => $exception->getFile(),
        "line" => $exception->getLine()
    ]);
    exit();
}