<?php
//interface for controllers
class Controller {
    public function view(string $file_name = null , $data = null, $message = null, $success = null, $error = null){

        $class_name = get_class($this);
        
        $dir_name = str_replace("Controller","",$class_name); 

        if($file_name == null || $file_name == ""){
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $file_name = $trace[1]['function'].".php"; 
        }else{
            if(substr($file_name,-4,4) != ".php"){
                $file_name .= ".php";
            }
        }
        // print_r($_SERVER);exit;
        $root_dir = dirname(__DIR__);
        $view_path = $root_dir."/views/".$dir_name."/".$file_name;

        if(file_exists($view_path)){
            include_once($view_path);
        }else{
            echo "Oops! Looks like there is no view file in ".$view_path;
        }
        
    }

    public function redirectToAction(string $action){
        if(method_exists($this,$action)){
            return $this -> $action();
        }else{
            return $this -> index();
        }
    }

    public function notFound(){
        echo '<h1 class="text-center">404</h1>';
        echo '<h2 class="text-center">The item you looking for is not here today</h2>';
    }
    
    public function internalError(Exception $ex){
		echo '<p class="error">An error has been spotted: '.$ex -> getMessage().'</p>';
	}
}
