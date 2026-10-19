<?php
class ProjectRepository extends Repository {
    public int $id;
    public string $name;
    public string $description;
    public int $created_by;
    public int $status_template_id;
}