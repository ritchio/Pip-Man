<?php
include_once "../system/DBConfig.php";
// include_once "../dtos/GenericResponseDto.php";
include_once "../dtos/ProjectDto.php";
include_once "../dtos/ProjectEpicDto.php";
include_once "../dtos/ProjectTaskDto.php";
class ProjectsService {
    private $pdo;
    public function __construct() {
        $this->pdo = DBConfig::getConnection();
    }

    public function getList(int $user_id = 0, int $archived = 0){
        
        $sql = "SELECT p.id, p.name, p.description, p.created_by, p.status_template_id, p.archived FROM project p";
        $params = [];
        $conditions = [];
        if($user_id > 0){
            $conditions[] = "(p.created_by = :user_id OR p.id IN (SELECT project_id FROM project_member WHERE user_id = :member_id))";
            $params[":user_id"] = $user_id;
            $params[":member_id"] = $user_id;
        }
        $conditions[] = "p.archived = :archived";
        $params[":archived"] = $archived;

        if(count($conditions) > 0){
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $query = $this->pdo->prepare($sql);
        $query -> execute($params);

        return $query -> fetchAll();
    }

    public function getById(int $id){
        try{
            $query = $this -> pdo -> prepare("SELECT p.id, p.name, p.description,p.status_template_id, p.created_by FROM project p WHERE p.id = :id");
            $query -> execute([":id"=>$id]);
            return  $query -> fetch();
        }catch(Exception $ex){
			echo $ex -> getMessage();
        }
    }

    public function create(array $project){
        
        $projectDto = ProjectDto::fromArray($project);

        //TODO: search for cleanest way to do it
        $query = $this -> pdo -> prepare("INSERT INTO project (id,name,description,created_by,status_template_id) 
        VALUES(:id,:name, :description, :created_by, :status_template_id)");

        $query -> execute($projectDto -> toArray());

        $id = $this->pdo->lastInsertId();

        //insert the first member
        if($id > 0){
            $query = $this -> pdo -> prepare("INSERT INTO project_member (user_id,project_id) VALUES(:user_id,:project_id)");
            $query -> execute([":user_id"=>$projectDto->created_by,":project_id"=>$id]);
        }

        $projectDto -> id = $id;
        return $projectDto;
    }

    public function delete(int $id){
        try{
            $query = $this -> pdo -> prepare("DELETE FROM project WHERE id = :id AND created_by = :user_id");
            $query -> execute([":id"=> $id, ":user_id" => $_SESSION["user_id"]]);

            return true;
        }catch(Exception $ex){
            return false;
        }
    }
    public function archive(int $project_id){
        try{
            $query = $this -> pdo -> prepare("UPDATE project SET archived = 1 WHERE id = :project_id");
            $query -> execute([":project_id"=>$project_id]);
            return $query -> rowCount() > 0;
        }catch(Exception $ex){
            echo $ex -> getMessage();
        }
    }

	//Epics
	public function getEpicsByProject(int $project_id){
		try{
			$query = $this -> pdo -> prepare("SELECT pe.id, pe.name,pe.description FROM project_epic pe WHERE project_id = :project_id");
			$query -> execute([":project_id"=>$project_id]);
			
			return $query -> fetchAll();
		}catch(Exception $ex){
			echo $ex->getMessage();
		}
	}
	
	public function createEpic(array $epic){
        
        $epicDto = ProjectEpicDto::fromArray($epic);

        //TODO: search for cleanest way to do it
        $query = $this -> pdo -> prepare("INSERT INTO project_epic (id,project_id,name,description,created_by) 
        VALUES(:id,:project_id,:name, :description, :created_by)");

        $query -> execute($epicDto -> toArray());

        $id = $this->pdo->lastInsertId();
        $epicDto -> id = $id;
        return $epicDto;
    }


    //Tasks
    public function createTask(array $task){
        
        $taskDto = ProjectTaskDto::fromArray($task);
        //TODO: search for cleanest way to do it
        $query = $this -> pdo -> prepare("INSERT INTO project_task (id,epic_id,name,description,created_by,asigned_to,status_id) 
        VALUES(:id,:epic_id,:name, :description, :created_by,:asigned_to,:status_id)");

        $query -> execute($taskDto -> toArray());

        $id = $this->pdo->lastInsertId();
        $taskDto -> id = $id;
        return $taskDto;
    }
    public function getTaskById(int $id){
        try{
            $query = $this -> pdo -> prepare("SELECT pt.id, pt.name,pt.description,pt.status_id,pt.asigned_to FROM project_task pt WHERE pt.id = :id");
            $query -> execute([":id"=>$id]);
            return  $query -> fetch();
        }catch(Exception $ex){
            echo $ex->getMessage();
        }
    }

    public function getTasksByEpic(int $epic_id){
		try{
			$query = $this -> pdo -> prepare("SELECT pt.id, pt.name,pt.status_id,pt.description FROM project_task pt WHERE epic_id = :epic_id");
			$query -> execute([":epic_id"=>$epic_id]);
			
			return $query -> fetchAll();
		}catch(Exception $ex){
			echo $ex->getMessage();
		}
	}

    public function getTasksByUser(int $user_id, int $status_id, int $project_id = 0){
		try{
            $sql = "SELECT pt.id, pt.name,pt.description,st.name as status, p.name as project , pe.name as epic, tu.username as assigned_user, pt.asigned_to
            FROM project_task pt 
            INNER JOIN project_status st ON pt.status_id = st.id
            INNER JOIN project_epic pe ON pt.epic_id = pe.id
            INNER JOIN project p ON pe.project_id = p.id
            LEFT JOIN user tu ON pt.asigned_to = tu.id
            WHERE pt.status_id = :status_id AND p.archived = 0 AND (pt.asigned_to = :asigned_to or pt.created_by = :created_by )";

            $params = [":status_id"=>$status_id,":asigned_to"=>$user_id,":created_by"=>$user_id];

            if($project_id > 0){
                $sql .= " AND pe.project_id = :project_id";
                $params[":project_id"] = $project_id;
            }

			$query = $this -> pdo -> prepare($sql);
			$query -> execute($params);
			
			return $query -> fetchAll();
		}catch(Exception $ex){
			echo $ex->getMessage();
		}
	}

    public function getTasksByProject(int $project_id,int $user_id){
		try{

            //TODO: add a check to see if the user is part of the project, otherwise return empty
            if(!$this->isProjectMember($project_id,$user_id)){
                return [];
            }
			$query = $this -> pdo -> prepare("SELECT pt.id, pt.name,pt.description,st.name as status ,pt.status_id, pe.name as epic, 
            tu.username as assigned_user, pt.asigned_to
            FROM project_task pt 
            INNER JOIN project_status st ON pt.status_id = st.id
            INNER JOIN project_epic pe ON pt.epic_id = pe.id
            LEFT JOIN user tu ON pt.asigned_to = tu.id
            WHERE pe.project_id = :project_id");
			$query -> execute([":project_id"=>$project_id]);
			
			return $query -> fetchAll();
		}catch(Exception $ex){
			echo $ex->getMessage();
		}
	}

    public function getStatusesByTemplate(int $template_id){
		try{
			$query = $this -> pdo -> prepare("SELECT ps.id, ps.name
            FROM project_status ps
            INNER JOIN project_status_rel sr ON ps.id = sr.status_id
            WHERE template_id = :template_id");
			$query -> execute([":template_id"=>$template_id]);
			
			return $query -> fetchAll();
		}catch(Exception $ex){
			echo $ex->getMessage();
		}
	}

    public function updateTask(ProjectTaskDto $task){
        try{
            $query = $this -> pdo -> prepare("SELECT * FROM project_task WHERE id = :id");
            $query -> execute([":id" => $task-> id]);
            
            $currentTask = $query -> fetch();

            if($currentTask != null){
                if($task -> name != "") $currentTask["name"] = $task -> name;
                if($task -> description != "") $currentTask["description"] = $task -> description;
                if($task -> status_id > 0) $currentTask["status_id"] = $task -> status_id;
                if($task -> asigned_to > 0) $currentTask["asigned_to"] = $task -> asigned_to;

                $query = $this -> pdo -> prepare("UPDATE project_task 
                SET  name = :name, description = :description, status_id = :status_id, asigned_to = :asigned_to, created_by = :created_by, epic_id = :epic_id
                WHERE id = :id");
                return $query -> execute($currentTask);

            }else{
                return false;
            }

        }catch(Exception $ex){
            echo $ex -> getMessage();
        }
    }

    public function importFromJson(mixed $json_object, int $project_id){
        try{
            $epic_num = 0;
            $task_num = 0;
            if($json_object != null && $this->isProjectOwner($project_id,$_SESSION["user_id"])){
                foreach($json_object as $epic){
                    if(array_key_exists("name",$epic) && array_key_exists("description",$epic)){
                        //save the epic
                        $epic["project_id"] = $project_id;
                        $epic_dto = $this -> createEpic($epic);
                        if(array_key_exists("tasks",$epic) && $epic_dto -> id > 0){
                            $epic_num += 1;
                            foreach($epic["tasks"] as $task){
                                if(array_key_exists("name",$task) && array_key_exists("description",$task)){
                                    //save the task
                                    $task["epic_id"] = $epic_dto -> id;
                                    $task_dto = $this -> createTask($task);
                                    $task_num += $task_dto -> id > 0? 1 : 0; 
                                }
                            }
                        }
                    }
                }
            }
            echo $epic_num." epics and ".$task_num." tasks imported.";
        }catch(Exception $ex){
            echo $ex -> getMessage();
        }
    }

    public function getStatusList(){
        
        $sql = "SELECT id, name FROM project_status";


        $query = $this->pdo->prepare($sql);
        $query -> execute();

        return $query -> fetchAll();
    }

    public function getMembersByProject(int $project_id){
        try{
            $query = $this -> pdo -> prepare("SELECT u.id, u.username FROM user u
            INNER JOIN project_member pm ON u.id = pm.user_id
            WHERE pm.project_id = :project_id");
            $query -> execute([":project_id"=>$project_id]);
            
            return $query -> fetchAll();
        }catch(Exception $ex){
            echo $ex->getMessage();
        }
    }

    

    public function isProjectOwner(int $project_id, int $user_id){
        try{
            $query = $this -> pdo -> prepare("SELECT p.id FROM project p  WHERE p.id = :project_id AND created_by = :user_id");
            $query -> execute([":project_id"=>$project_id,":user_id" => $user_id]);
            $result = $query -> fetchAll();

            return $result != null;
        }catch(Exception $ex){
            echo $ex -> getMessage();
        }
    }
    public function isProjectMember(int $project_id, int $user_id){
        try{
            $query = $this -> pdo -> prepare("SELECT pm.project_id FROM project_member pm  WHERE pm.project_id = :project_id AND user_id = :user_id");
            $query -> execute([":project_id"=>$project_id,":user_id" => $user_id]);
            $result = $query -> fetchAll();

            return $result != null;
        }catch(Exception $ex){
            echo $ex -> getMessage();
        }
    }
}
