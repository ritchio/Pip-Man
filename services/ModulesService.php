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
 
}