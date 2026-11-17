<?php
require_once __DIR__ . '/../../system/DBConfig.php';

class UserModel {
    private $pdo;

    public function __construct(?PDO $pdo = null) {
        $this->pdo = $pdo ?? DBConfig::getConnection();
    }

    public function getList() {
        $query = $this->pdo->prepare("SELECT id, username FROM user ORDER BY id");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) {
        $query = $this->pdo->prepare("SELECT id, username FROM user WHERE id = :id");
        $query->execute([":id" => $id]);

        return $query->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByUsername(string $username) {
        $query = $this->pdo->prepare("SELECT id FROM user WHERE username = :username");
        $query->execute([":username" => $username]);

        return $query->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data) {
        $query = $this->pdo->prepare(
            "INSERT INTO user (username, password) VALUES (:username, :password)"
        );
        $query->execute([
            ":username" => $data["username"],
            ":password" => $data["password"]
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data) {
        if (array_key_exists("password", $data)) {
            $query = $this->pdo->prepare(
                "UPDATE user SET username = :username, password = :password WHERE id = :id"
            );
            $query->execute([
                ":id" => $id,
                ":username" => $data["username"],
                ":password" => $data["password"]
            ]);
        } else {
            $query = $this->pdo->prepare(
                "UPDATE user SET username = :username WHERE id = :id"
            );
            $query->execute([
                ":id" => $id,
                ":username" => $data["username"]
            ]);
        }

        return $query->rowCount() > 0;
    }

    public function delete(int $id) {
        $query = $this->pdo->prepare("DELETE FROM user WHERE id = :id");
        $query->execute([":id" => $id]);

        return $query->rowCount() > 0;
    }
}
