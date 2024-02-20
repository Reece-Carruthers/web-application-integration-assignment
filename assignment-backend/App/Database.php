<?php

namespace App;

use PDO;

/**
 * Class handles the connection to the database
 * @author Reece Carruthers (w19011575)
 */
class Database
{
    public function __construct(private string $fileName)
    {
    }

    /**
     * Returns a PDO connection to the database
     * @return PDO
     */
    public function getConnection(): PDO
    {
        $PDO = new PDO("sqlite:$this->fileName", options: [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $PDO;
    }
}