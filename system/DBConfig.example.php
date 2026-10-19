<?php
/**
 * Database configuration class example.
 * replace the placeholder values with your actual database credentials.
 * Make sure to keep this file secure and do not expose it publicly.
 * Do not commit this file to version control.
 * Rename this file to DBConfig.php after filling in your actual database credentials.
 */
class DBConfig
{

    private const DB_HOST = 'localhost';
    private const DB_NAME = 'your_database';
    private const DB_USER = 'your_username';
    private const DB_PASS = 'your_password';
    private const DB_CHARSET = 'utf8mb4';

    public static function getConnection(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            self::DB_HOST,
            self::DB_NAME,
            self::DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
    }
}
