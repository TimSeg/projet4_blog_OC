<?php

namespace App\Model\Factory;

use Pdo;

/**
 * Class PdoFactory
 * Creates the Connection if it doesn't exist
 * @package App\Model
 */
class PdoFactory
{
    /**
     * Stores the Connection
     * @var null
     */
    private static $Pdo = null;

    /**
     * Returns the Connection if it exists or creates it before returning it
     * @return Pdo|null
     */
    public static function getPdo()
    {
        require_once '../config/db.php';

        if (self::$Pdo === null) {
            self::$Pdo = new Pdo(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
            self::$Pdo->exec('SET NAMES UTF8');
        }

        return self::$Pdo;
    }
}