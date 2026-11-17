<?php 
require_once("../services/ModulesService.php");
require_once("../system/Controller.php");

class ModulesController extends Controller{
    private $modulesService;    
    public function __construct(){
        $this -> modulesService = new ModulesService();
    }
    public function handleRequest() {
        // This method can be used to handle any common logic for all actions in this controller
    }

    public function index() {
        try{
            $modules = $this -> modulesService -> getList();
            return $this -> view(data:$modules);

        }catch(Exception $ex){
            echo $ex;
        }
    }
}