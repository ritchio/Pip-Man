<?php

require_once __DIR__ . '/../User.php';

class Initial
{
    protected \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function up(): void
    {
        $className = 'User';

        if (!class_exists($className)) {
            throw new \RuntimeException(sprintf('Entity class "%s" not found.', $className));
        }

        $reflection = new \ReflectionClass($className);
        $tableName = $reflection->getShortName();
        $columns = $this->buildColumns($reflection);

        $sql = sprintf(
            'CREATE TABLE IF NOT EXISTS `%s` (%s) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;',
            $tableName,
            implode(', ', $columns)
        );

        $this->pdo->exec($sql);
    }

    public function down(): void
    {
        $className = 'User';

        if (!class_exists($className)) {
            require_once __DIR__ . '/../User.php';
        }

        $reflection = new \ReflectionClass($className);
        $tableName = $reflection->getShortName();

        $this->pdo->exec(sprintf('DROP TABLE IF EXISTS `%s`;', $tableName));
    }

    protected function buildColumns(\ReflectionClass $reflection): array
    {
        $columns = [];

        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();

            if ($name === 'id') {
                $columns[] = '`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY';
                continue;
            }

            $type = $this->resolveColumnType($property);
            $null = 'NULL';

            $propertyType = $property->getType();
            if ($propertyType instanceof \ReflectionNamedType && !$propertyType->allowsNull()) {
                $null = 'NOT NULL';
            }

            $columns[] = sprintf('`%s` %s %s', $name, $type, $null);
        }

        return $columns;
    }

    protected function resolveColumnType(\ReflectionProperty $property): string
    {
        $type = $property->getType();

        if ($type instanceof \ReflectionNamedType) {
            $typeName = $type->getName();
            switch (strtolower($typeName)) {
                case 'int':
                case 'integer':
                    return 'INT';
                case 'bool':
                case 'boolean':
                    return 'TINYINT(1)';
                case 'float':
                case 'double':
                    return 'DOUBLE';
                case 'datetime':
                    return 'DATETIME';
                case 'string':
                case 'array':
                case 'object':
                default:
                    return 'TEXT';
            }
        }

        return 'TEXT';
    }
}
