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
}