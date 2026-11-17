<?php
require_once __DIR__ . '/../data/models/ClientModel.php';

class ClientsService {
    private $clientModel;

    public function __construct() {
        $this->clientModel = new ClientModel();
    }

    public function getList() {
        return $this->clientModel->getList();
    }

    public function getById(int $id) {
        if ($id <= 0) {
            throw new InvalidArgumentException("Invalid client ID.");
        }

        return $this->clientModel->getById($id);
    }

    public function create(array $data) {
        return $this->clientModel->create($this->validate($data));
    }

    public function update(int $id, array $data) {
        if ($id <= 0 || $this->clientModel->getById($id) === null) {
            throw new InvalidArgumentException("Client not found.");
        }

        return $this->clientModel->update($id, $this->validate($data));
    }

    public function delete(int $id) {
        if ($id <= 0) {
            throw new InvalidArgumentException("Invalid client ID.");
        }

        return $this->clientModel->delete($id);
    }

    private function validate(array $data): array {
        $name = $data["name"] ?? null;
        $lastName = $data["last_name"] ?? null;

        if (!is_string($name) || trim($name) === "") {
            throw new InvalidArgumentException("Client name is required.");
        }

        if (!is_string($lastName) || trim($lastName) === "") {
            throw new InvalidArgumentException("Client last name is required.");
        }

        foreach (["phone_number", "social_link"] as $field) {
            if (isset($data[$field]) && !is_string($data[$field])) {
                throw new InvalidArgumentException("Invalid client data.");
            }
        }

        return [
            "name" => trim($name),
            "last_name" => trim($lastName),
            "phone_number" => trim($data["phone_number"] ?? ""),
            "social_link" => trim($data["social_link"] ?? "")
        ];
    }
}
