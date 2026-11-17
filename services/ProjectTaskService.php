<?php
class ProjectTaskService {
    private $pdo;

    public function __construct() {
        $this->pdo = DBConfig::getConnection();
    }

    public function createTask(array $task) {
        $taskDto = ProjectTaskDto::fromArray($task);
        $query = $this->pdo->prepare("INSERT INTO project_task (id,epic_id,name,description,created_by,asigned_to,status_id) \n        VALUES(:id,:epic_id,:name, :description, :created_by,:asigned_to,:status_id)");
        $query->execute($taskDto->toArray());
        $id = $this->pdo->lastInsertId();
        $taskDto->id = $id;
        return $taskDto;
    }

    public function getTaskById(int $id) {
        try {
            $query = $this->pdo->prepare("SELECT pt.id, pt.name,pt.description,pt.status_id,pt.asigned_to FROM project_task pt WHERE pt.id = :id");
            $query->execute([":id" => $id]);
            return $query->fetch();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTasksByEpic(int $epic_id) {
        try {
            $query = $this->pdo->prepare("SELECT pt.id, pt.name,pt.status_id,pt.description FROM project_task pt WHERE epic_id = :epic_id");
            $query->execute([":epic_id" => $epic_id]);
            return $query->fetchAll();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTasksByUser(int $user_id, int $status_id, int $project_id = 0) {
        try {
            $sql = "SELECT pt.id, pt.name,pt.description,st.name as status, p.name as project , pe.name as epic, tu.username as assigned_user, pt.asigned_to\n            FROM project_task pt \n            INNER JOIN project_status st ON pt.status_id = st.id\n            INNER JOIN project_epic pe ON pt.epic_id = pe.id\n            INNER JOIN project p ON pe.project_id = p.id\n            LEFT JOIN user tu ON pt.asigned_to = tu.id\n            WHERE pt.status_id = :status_id AND p.archived = 0 AND (pt.asigned_to = :asigned_to or pt.created_by = :created_by )";
            $params = [":status_id" => $status_id, ":asigned_to" => $user_id, ":created_by" => $user_id];
            if($project_id > 0){
                $sql .= " AND pe.project_id = :project_id";
                $params[":project_id"] = $project_id;
            }

            $query = $this->pdo->prepare($sql);
            $query->execute($params);
            return $query->fetchAll();

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTasksByProject(int $project_id, int $user_id) {
        try {
            if (!$this->isProjectMember($project_id, $user_id)) {
                return [];
            }

            $sql = "SELECT pt.id, pt.name,pt.description,st.name as status ,pe.name as epic, \n            tu.username as assigned_user, pt.asigned_to\n            FROM project_task pt \n            INNER JOIN project_status st ON pt.status_id = st.id\n            INNER JOIN project_epic pe ON pt.epic_id = pe.id\n            LEFT JOIN user tu ON pt.asigned_to = tu.id\n            WHERE pe.project_id = :project_id";

            $params = [":project_id" => $project_id];

            $query = $this->pdo->prepare($sql);
            $query->execute($params);

            return $query->fetchAll();

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function isProjectOwner(int $project_id, int $user_id) {
        try {
            $query = $this->pdo->prepare("SELECT p.id FROM project p  WHERE p.id = :project_id AND created_by = :user_id");
            $query->execute([":project_id" => $project_id, ":user_id" => $user_id]);
            $result = $query->fetchAll();

            return $result != null;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function isProjectMember(int $project_id, int $user_id) {
        try {
            $query = $this->pdo->prepare("SELECT pm.project_id FROM project_member pm  WHERE pm.project_id = :project_id AND user_id = :user_id");
            $query->execute([":project_id" => $project_id, ":user_id" => $user_id]);
            $result = $query->fetchAll();

            return $result != null;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function updateTask(ProjectTaskDto $task) {
        try {
            $query = $this->pdo->prepare("SELECT * FROM project_task WHERE id = :id");
            $query->execute([":id" => $task->id]);

            $currentTask = $query->fetch();

            if($currentTask != null){
                if($task->name != \"\") $currentTask[\"name\"] = $task->name;
                if($task->description != \"\") $currentTask[\"description\"] = $task->description;
                if($task->status_id > 0) $currentTask[\"status_id\"] = $task->status_id;
                if($task->asigned_to > 0) $currentTask[\"asigned_to\"] = $task->asigned_to;
                $query = $this->pdo->prepare("UPDATE project_task \n                SET  name = :name, description = :description, status_id = :status_id, asigned_to = :asigned_to, created_by = :created_by, epic_id = :epic_id\n                WHERE id = :id");
                return $query->execute($currentTask);
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function importFromJson(mixed $json_object, int $project_id) {
        try {
            $epic_num = 0;
            $task_num = 0;
            if($json_object != null && $this->isProjectOwner($project_id, $_SESSION["user_id"])) {
                foreach($json_object as $epic){
                    if(array_key_exists("name", $epic) && array_key_exists("description", $epic)){
                        //save the epic
                        $epic["project_id"] = $project_id;
                        $epic_dto = $this->createEpic($epic);
                        if($epic_dto->id > 0){
                            $epic_num += 1;
                            foreach($epic["tasks"] as $task){
                                if(array_key_exists("name", $task) && array_key_exists("description", $task)){
                                    //save the task
                                    $task["epic_id"] = $epic_dto->id;
                                    $task_dto = $this->createTask($task);
                                    $task_num += $task_dto->id > 0 ? 1 : 0; 
                                }
                            }
                        }
                    }
                }
            }

            echo $epic_num." epics and ".$task_num." tasks imported.";
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}