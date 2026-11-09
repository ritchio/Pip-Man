<?php
class ProjectTaskDto{
    
    public function __construct(
    public int $id = 0,
    public int $epic_id = 0,
    public string $name = "",
    public string $description = "",
    public int $created_by = 0,
    public int $status_id = 0,
    public int $asigned_to = 0
    ){}

    public static function fromArray(array $data):self
    {
        return new self(
            id:$data["id"]?? 0,
            epic_id:$data["epic_id"]?? 0,
            name:$data["name"]??"",
            description:$data["description"]??"",
            status_id:$data["status_id"] ?? 1,
            created_by:$data["created_by"] ?? $_SESSION["user_id"],
            asigned_to:$data["asigned_to"] ?? 0
        );
    }

    public function toArray(): array {
        return [
            "id" => $this -> id > 0? $this -> id : null,
            "epic_id" => $this -> epic_id > 0? $this -> epic_id : null,
            "name" => $this -> name,
            "description" => $this -> description,
            "status_id" => $this -> status_id,
            "created_by" => $this -> created_by > 0? $this -> created_by : $_SESSION["user_id"],
            "asigned_to" => $this -> asigned_to
        ];
    }
}