<?php
require_once __DIR__ . '/../../system/DBConfig.php';

class ProductModel {
    private $pdo;

    public function __construct() {
        $this->pdo = DBConfig::getConnection();
    }

    public function getList() {
        try {
            $query = $this->pdo->prepare("SELECT id, name, active FROM product ORDER BY id");
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching products: " . $e->getMessage());
        }
    }

    public function getById(int $id) {
        try {
            $query = $this->pdo->prepare(
                "SELECT id, name, active FROM product WHERE id = :id"
            );
            $query->execute([":id" => $id]);

            return $query->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            throw new Exception("Error fetching product: " . $e->getMessage());
        }
    }

    public function create(array $data) {
        try {
            $query = $this->pdo->prepare(
                "INSERT INTO product (name, active) VALUES (:name, :active)"
            );
            $query->execute([
                ":name" => $data["name"],
                ":active" => $data["active"]
            ]);

            return (int)$this->pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error creating product: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data) {
        try {
            $query = $this->pdo->prepare(
                "UPDATE product SET name = :name, active = :active WHERE id = :id"
            );
            $query->execute([
                ":id" => $id,
                ":name" => $data["name"],
                ":active" => $data["active"]
            ]);

            return $query->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception("Error updating product: " . $e->getMessage());
        }
    }

    public function delete(int $id) {
        try {
            $query = $this->pdo->prepare("DELETE FROM product WHERE id = :id");
            $query->execute([":id" => $id]);

            return $query->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception("Error deleting product: " . $e->getMessage());
        }
    }
}
