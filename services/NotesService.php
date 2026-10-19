<?php
include_once "../system/DBConfig.php";

class NotesService {
    private $pdo;
    public function __construct() {
        $this->pdo = DBConfig::getConnection();
    }

    public function getList(int $user_id = 0, bool $only_active){
        
        $sql = "SELECT n.id, n.description, n.active FROM user_note n";
        $params = [];
        if($user_id > 0){
            $sql .= " WHERE n.user_id = :user_id";
            $params[":user_id"] = $user_id;
        }

        if($only_active){
            $sql .= " and n.active = 1";
        }

        $sql .= " ORDER BY id desc";
        $query = $this->pdo->prepare($sql);
        $query -> execute($params);

        return $query -> fetchAll();
    }


    public function create(array $note){
        $note["user_id"] = $_SESSION["user_id"];
        $note["active"] = 1;
        $query = $this -> pdo -> prepare("INSERT INTO user_note (description,user_id,active) 
        VALUES(:description, :user_id,:active)");

        $query -> execute($note);

        return true;
    }

    public function delete(int $id){
        try{
            $query = $this -> pdo -> prepare("DELETE FROM user_note WHERE id = :id AND user_id = :user_id");
            $query -> execute([":id"=> $id, ":user_id" => $_SESSION["user_id"]]);

            return true;
        }catch(Exception $ex){
            return false;
        }
    }

    public function deactivate(int $id){
        try{
            $query = $this -> pdo -> prepare("UPDATE user_note SET active = 0 WHERE id = :id AND user_id = :user_id");
            $query -> execute([":id"=> $id, ":user_id" => $_SESSION["user_id"]]);

            return true;
        }catch(Exception $ex){
            return false;
        }
    }
}