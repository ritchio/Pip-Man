<?php
class ProjectEpicService {
    private $pdo;

    public function __construct() {
        $this->pdo = DBConfig::getConnection();
    }

    public function getEpicsByProject(int $project_id) {
        $sql = "SELECT pe.id, pe.name, pe.description FROM project_epic pe WHERE project_id = :project_id";
        $params = [":project_id" => $project_id];
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return $query->fetchAll();
    }

    public function createEpic(array $epic) {
        $epicDto = ProjectEpicDto::fromArray($epic);
        $query = $this->pdo->prepare("INSERT INTO project_epic (id,project_id,name,description,created_by) \n        VALUES(:id,:project_id,:name, :description, :created_by)");
        $query->execute($epicDto->toArray());
        $id = $this->pdo->lastInsertId();
        $epicDto->id = $id;
        return $epicDto;
    }
}