<?php
class ProjectDto{
    
    public function __construct(
    public int $id,
    public string $name,
    public string $description,
    public int $created_by,
    public int $status_template_id,
    public int $archived,
    ){}

    public static function fromArray(array $data):self
    {
        return new self(
            id:$data["id"]?? 0,
            name:$data["name"]??"",
            description:$data["description"]??"",
            created_by:$data["created_by"]?? 0,
            status_template_id:$data["status_template_id"]?? 0,
            archived:$data["archived"]?? 0
        );
    }

    public function toArray(): array {
        return [
            "id" => $this -> id > 0? $this -> id : null,
            "name" => $this -> name,
            "description" => $this -> description,
            "created_by" => $this -> created_by,
            "status_template_id" => $this -> status_template_id,
            "archived" => $this -> archived
        ];
    }
}