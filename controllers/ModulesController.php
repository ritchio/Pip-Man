<?php 
require_once("../services/ModulesService.php");
require_once("../system/Controller.php");

class ModulesController extends Controller{
    private $modulesService;    
    public function __construct(){
        $this -> modulesService = new ModulesService();
    }

    public function index() {
        try{
            $modules = $this -> modulesService -> getList();
            return $this -> view(data:$modules);

        }catch(Exception $ex){
            echo $ex;
        }
    }
    public function create($data = null) {
        try{
            if($data != null)
            {
                $project = $this -> modulesService -> create($data);
                if($project -> id > 0){
                    return $this -> index();
                }
            }

            return $this -> view();

        }catch(Exception $ex){
            echo $ex;
        }
    }

    public function edit($id = null, $data = null) {
        try{
            if($id == null || !is_numeric($id) || (int)$id <= 0){
                return $this -> notFound();
            }

            $module = $this -> modulesService -> getById((int)$id);
            if($module == null){
                return $this -> notFound();
            }

            if($data != null){
                $this -> modulesService -> update((int)$id, $data);
                return $this -> index();
            }

            return $this -> view(data:$module);
        }catch(Exception $ex){
            echo $ex;
        }
    }

    public function delete($id = null) {
        try{
            if($id != null && is_numeric($id) && (int)$id > 0){
                $this -> modulesService -> delete((int)$id);
            }

            return $this -> index();
        }catch(Exception $ex){
            echo $ex;
        }
    }
}