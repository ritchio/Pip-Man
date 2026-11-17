<?php
require_once("../system/DBConfig.php");

class ModuleModel {
    private $pdo;

    public function __construct() {
        $this->pdo = DBConfig::getConnection();
    }

    public function getList() {
        try {
            $sql = "SELECT * FROM module";
            $query = $this->pdo->prepare($sql);
            $query -> execute();

            return $query -> fetchAll();
        } catch (Exception $e) {
            throw new Exception("Error fetching modules: " . $e->getMessage());
        }
    }

    public function getById(int $id) {
        try {
            $query = $this->pdo->prepare("SELECT * FROM module WHERE id = :id");
            $query->execute([":id" => $id]);

            return $query->fetch();
        } catch (Exception $e) {
            throw new Exception("Error fetching module: " . $e->getMessage());
        }
    }

    public function create(array $data) {
        try {
            $query = $this->pdo->prepare(
                "INSERT INTO module (name, restricted, url, active, list_order) VALUES (:name, :restricted, :url, :active, :list_order)"
            );
            $query->execute([
                ":name" => $data["name"],
                ":restricted" => $data["restricted"],
                ":url" => $data["url"],
                ":active" => 1,
                ":list_order" => 0
            ]);


            return $this->getById($this->pdo->lastInsertId());
        } catch (Exception $e) {
            throw new Exception("Error creating module: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data) {
        try {
            $query = $this->pdo->prepare(
                "UPDATE module SET name = :name, restricted = :restricted, url = :url, active = :active, list_order = :list_order WHERE id = :id"
            );
            $query->execute([
                ":id" => $id,
                ":name" => $data["name"],
                ":restricted" => $data["restricted"],
                ":url" => $data["url"],
                ":active" => 1,
                ":list_order" => 0
            ]);

            return true;
        } catch (Exception $e) {
            throw new Exception("Error updating module: " . $e->getMessage());
        }
    }

    public function delete(int $id) {
        try {
            $query = $this->pdo->prepare("DELETE FROM module WHERE id = :id");
            $query->execute([":id" => $id]);

            return $query->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception("Error deleting module: " . $e->getMessage());
        }
    }
}