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

    public function update(int $id, array $data) {
        if($id <= 0 || !isset($data["name"]) || !is_string($data["name"]) ||
            trim($data["name"]) === "" || !isset($data["restricted"]) ||
            (!is_string($data["restricted"]) && !is_int($data["restricted"])) ||
            !in_array((string)$data["restricted"], ["0", "1"], true)){
            throw new InvalidArgumentException("Invalid module data.");
        }

        return $this->moduleModel->update($id, [
            "name" => trim($data["name"]),
            "restricted" => (int)$data["restricted"]
        ]);
    }

    public function delete(int $id) {
        if($id <= 0){
            throw new InvalidArgumentException("Invalid module ID.");
        }

        return $this->moduleModel->delete($id);
    }
}