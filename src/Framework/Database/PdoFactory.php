<?php

declare(strict_types=1);

namespace App\Framework\Database;

use PDO;

final class PdoFactory
{
    public function create(
        string $host,
        int $port,
        string $database,
        string $username,
        string $password,
    ): PDO {
        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s', $host, $port, $database);

        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
