<?php
include_once("../services/ProjectsService.php");
include_once("../system/Controller.php");
include_once("../dtos/ProjectTaskDto.php");
class ProjectsController extends Controller{
    private $projectsService;    
    public function __construct(){
        $this -> projectsService = new ProjectsService();
    }
    public function handleRequest() {
        // This method can be used to handle any common logic for all actions in this controller
    }

    public function index() {
        try{
            $projects = $this -> projectsService -> getList($_SESSION["user_id"]);
            $tasks = [];
            if($projects != null){
                foreach($projects as $key => $project){
                    $res_tasks = $this -> projectsService -> getTasksByProject($project["id"],$_SESSION["user_id"]);
                    $statuses = $this -> projectsService -> getStatusesByTemplate($project["status_template_id"]);
                    $members = $this -> projectsService -> getMembersByProject($project["id"]);
                    $tasks_by_status = [];
                    foreach($statuses as $skey => $status){
                        $tasks_by_status[$skey] = [];
                    }

                    foreach($res_tasks as $tkey => $task){
                        $tasks_by_status[$task["status_id"]][$tkey] = $task;
                    }

                    $tasks[$key] = $project;
                    $tasks[$key]["statuses"] = $statuses;
                    $tasks[$key]["tasks"] = $tasks_by_status;
                    $tasks[$key]["members"] = $members;
                }
            }
            return $this -> view(data:$tasks);

        }catch(Exception $ex){
            echo $ex;
        }
    }

    public function manage(){
         try{
            $projects = $this->projectsService -> getList($_SESSION["user_id"]);
            return $this -> view(data:$projects);

        }catch(Exception $ex){
            echo $ex;
        }
    }

    public function details($id) {
        try{
			$project = $this -> projectsService -> getById($id);
			$epics = $this->projectsService -> getEpicsByProject($id);
			
            if($epics != null){
                foreach($epics as $key => $epic){
                    $tasks = $this -> projectsService -> getTasksByEpic($epic["id"]);
                    $epics[$key]["tasks"] = $tasks;
                }
            }
            $data = ["id"=>$project["id"],"name"=>$project["name"],"epics"=>$epics];
			return $this -> view(data:$data);
		}catch(Exception $ex){
			$this -> internalError($ex);
		}
    }

    public function edit(int $id) {
       if($id != null && $id > 0){
            $project = $this -> projectsService -> getById($id);
       }else{
        return $this -> notFound();
       }
    }

    public function create($data=null) {
        if($data != null)
        {
            $data["created_by"] = $_SESSION["user_id"];
            $data["status_template_id"] = 1;
            $project = $this -> projectsService -> create($data);
            if($project -> id > 0){
                return $this -> index();
            }
        }

        return $this -> view();
    }

    public function delete($id=null){
        if($id != null && $id > 0){
            $this -> projectsService -> delete($id);
        }

        return $this -> index();
    }
    
    public function archive($id=null){
        if($id != null && $id > 0){
            $this -> projectsService -> archive($id);
        }

        return $this -> index();
    }
    //Epics
     public function createEpic(int $id,$data=null) {
        if($data != null)
        {
            $data["created_by"] = $_SESSION["user_id"];
            $data["project_id"] = $id;
            $epic= $this -> projectsService -> createEpic($data);
            if($epic -> id > 0){
                return $this -> details($id);
            }
        }
        return $this -> view(data:["id"=>$id]);
    }

    //Tasks
    public function tasks(int $id){
        $tasks = $this -> projectsService -> getTasksByEpic($id);

        return $this -> view(data:$tasks);
    }
    public function tasksByStatus(int $status_id, int $project_id = 0){
        $tasks = $this -> projectsService -> getTasksByUser($_SESSION["user_id"],$status_id, $project_id);
        $projects = $this -> projectsService -> getList($_SESSION["user_id"]);
        $data = [];
        $data["projects"] = $projects;
        $data["tasks"] = $tasks;
        $data["selected_project"] = $project_id;
        $data["selected_status"] = $status_id;
        $data["statuses"] = $this -> projectsService -> getStatusList(); 
        return $this -> view(data:$data);
    }
    public function createTask(int $id, $data = null){
        if($data != null)
        {
            $data["created_by"] = $_SESSION["user_id"];
            $data["epic_id"] = $id;
            $data["status_id"] = 1;
            $data["asigned_to"] = $_SESSION["user_id"];
            $epic= $this -> projectsService -> createTask($data);
            if($epic -> id > 0){
                return $this -> tasks($id);
            }
        }
        return $this -> view(data:["id"=>$id]);
    }

    public function editTask(int $id, $data = null){
        $message = null;
        if($data != null)
        {
            $taskDto = new ProjectTaskDto();
            $taskDto -> name = $data["name"];
            $taskDto -> description = $data["description"];
            $taskDto -> id = $id;
            $success = $this -> projectsService -> updateTask($taskDto);
            $message = $success ? "<p class='text-center bg-success p-3'>Task updated successfully</p>" : "<p class='text-center bg-danger p-3'>Error updating task</p>";
        }
        $task = $this -> projectsService -> getTaskById($id);
        return $this -> view(data:$task, message:$message);
    }

    public function createTaskFromProject(int $id, $data = null){

        if($data != null)
        {
            $data["created_by"] = $_SESSION["user_id"];
            $task= $this -> projectsService -> createTask($data);
            if($task -> id > 0){
                return $this -> index();
            }
        }
        $epics = $this -> projectsService -> getEpicsByProject($id);
        $statuses = $this -> projectsService -> getStatusList();
        $members = $this -> projectsService -> getMembersByProject($id);
        return $this -> view(data:["id"=>$id, "epics"=>$epics, "statuses"=>$statuses, "members"=>$members]);
    }

    public function changeTaskStatus(int $task_id, int $status_id, $data = null){

        $task = new ProjectTaskDto();
        $task -> status_id = $status_id;
        $task -> id = $task_id;
        $success = $this -> projectsService -> updateTask($task);

        if($data != null && array_key_exists("redirect_to_action",$data)){
            $action = $data["redirect_to_action"];
            return $this -> $action(intval($data["status_id"]),array_key_exists("project_id",$data)?intval($data["project_id"]):0);
        }else{
            return $this -> index();
        }
    }

    public function importTasks(int $project_id,$data=null){
        
        $view_data = ["project_id"=>$project_id];
        
        if($_FILES != null && array_key_exists("json_file",$_FILES)){

            $json_string = file_get_contents($_FILES["json_file"]["tmp_name"]);
            
            $json_object = json_decode($json_string,true);
        
            $this -> projectsService -> importFromJson($json_object,$project_id);
           
        }

        return $this -> view(data:$view_data);
    }

    public function asignTask(int $task_id, int $user_id){
        $task = new ProjectTaskDto();
        $task -> asigned_to = $user_id;
        $task -> id = $task_id;
        $success = $this -> projectsService -> updateTask($task);
        return $this -> index();
    }
}

?>
