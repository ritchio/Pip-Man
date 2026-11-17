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

    public function update(int $id, array $data) {
        try {
            $query = $this->pdo->prepare(
                "UPDATE module SET name = :name, restricted = :restricted WHERE id = :id"
            );
            $query->execute([
                ":id" => $id,
                ":name" => $data["name"],
                ":restricted" => $data["restricted"]
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