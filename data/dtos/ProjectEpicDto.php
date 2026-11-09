<?php
class ProjectEpicDto{
    
    public function __construct(
    public int $id,
    public int $project_id,
    public string $name,
    public string $description,
    public int $created_by,
    ){}

    public static function fromArray(array $data):self
    {
        return new self(
            id:$data["id"]?? 0,
            name:$data["name"]??"",
            project_id:$data["project_id"]??"",
            description:$data["description"]??"",
            created_by:$data["created_by"]?? $_SESSION["user_id"],
        );
    }

    public function toArray(): array {
        return [
            "id" => $this -> id > 0? $this -> id : null,
            "project_id" => $this -> project_id > 0? $this -> project_id : null,
            "name" => $this -> name,
            "description" => $this -> description,
            "created_by" => $this -> created_by > 0? $this -> created_by : $_SESSION["user_id"],
        ];
    }
}
