<?php

namespace App;

/**
 * A basic logger to log what is happening in the application
 *
 * @author Reece Carruthers (w19011575)
 */
abstract class Logger
{


    /**
     * Logs a message to the log file
     * @param string $message
     * @return void
     */
    public static function logMessage(string $message): void
    {

        $timestamp = date("d-m-Y H:i:s");

        $method = Request::method();
        $parameters = print_r(Request::params(), true);
        $headers = print_r(getallheaders(), true);

        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


        $logMessage = "$timestamp\nMethod: $method\nURL: $url\nParameters: $parameters\nHeaders: $headers\nMessage: $message\nResponse Code: " . http_response_code() . "\n\n";

        file_put_contents(LOGFILE, $logMessage, FILE_APPEND);
    }



}