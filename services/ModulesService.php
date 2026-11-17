<?php
require_once("../data/models/ModuleModel.php");
class ModulesService {
    private $moduleModel;

    public function __construct() {
        $this->moduleModel = new ModuleModel();
    }

    public function getList() {
        return $this->moduleModel->getList();
    }

    public function getById(int $id) {
        return $this->moduleModel->getById($id);
    }

    public function create(array $data) {
        if(!isset($data["name"]) || !is_string($data["name"]) || trim($data["name"]) === "" ||
            !isset($data["restricted"]) || (!is_string($data["restricted"]) && !is_int($data["restricted"])) ||
            !in_array((string)$data["restricted"], ["0", "1"], true)){
            throw new InvalidArgumentException("Invalid module data.");
        }

        return $this->moduleModel->create([
            "name" => trim($data["name"]),
            "restricted" => (int)$data["restricted"],
            "url" => trim($data["url"]),
            "active" => 1,
            "list_order" => $data["list_order"] ?? 0
        ]);
    }

    public function update(int $id, array $data) {
        if($id <= 0 || !isset($data["name"]) || !is_string($data["name"]) ||
            trim($data["name"]) === "" || !isset($data["restricted"]) ||
            (!is_string($data["restricted"]) && !is_int($data["restricted"])) ||
            !in_array((string)$data["restricted"], ["0", "1"], true)){
            throw new InvalidArgumentException("Invalid module data.");
        }

        return $this->moduleModel->update($id, [
            "name" => trim($data["name"]),
            "restricted" => (int)$data["restricted"],
            "url" => trim($data["url"]),
            "active" => (int)$data["active"],
            "list_order" => $data["list_order"] ?? 0
        ]);
    }

    public function delete(int $id) {
        if($id <= 0){
            throw new InvalidArgumentException("Invalid module ID.");
        }

        return $this->moduleModel->delete($id);
    }
}