<?php
require_once __DIR__ . '/../../system/DBConfig.php';

class ClientModel {
    private $pdo;

    public function __construct() {
        $this->pdo = DBConfig::getConnection();
    }

    public function getList() {
        try {
            $query = $this->pdo->prepare(
                "SELECT id, name, last_name, phone_number, social_link FROM client ORDER BY id"
            );
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching clients: " . $e->getMessage());
        }
    }

    public function getById(int $id) {
        try {
            $query = $this->pdo->prepare(
                "SELECT id, name, last_name, phone_number, social_link FROM client WHERE id = :id"
            );
            $query->execute([":id" => $id]);

            return $query->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            throw new Exception("Error fetching client: " . $e->getMessage());
        }
    }

    public function create(array $data) {
        try {
            $query = $this->pdo->prepare(
                "INSERT INTO client (name, last_name, phone_number, social_link)
                VALUES (:name, :last_name, :phone_number, :social_link)"
            );
            $query->execute([
                ":name" => $data["name"],
                ":last_name" => $data["last_name"],
                ":phone_number" => $data["phone_number"],
                ":social_link" => $data["social_link"]
            ]);

            return (int)$this->pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error creating client: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data) {
        try {
            $query = $this->pdo->prepare(
                "UPDATE client
                SET name = :name, last_name = :last_name, phone_number = :phone_number, social_link = :social_link
                WHERE id = :id"
            );
            $query->execute([
                ":id" => $id,
                ":name" => $data["name"],
                ":last_name" => $data["last_name"],
                ":phone_number" => $data["phone_number"],
                ":social_link" => $data["social_link"]
            ]);

            return $query->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception("Error updating client: " . $e->getMessage());
        }
    }

    public function delete(int $id) {
        try {
            $query = $this->pdo->prepare("DELETE FROM client WHERE id = :id");
            $query->execute([":id" => $id]);

            return $query->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception("Error deleting client: " . $e->getMessage());
        }
    }
}
