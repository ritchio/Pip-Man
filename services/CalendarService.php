<?php
include_once "../system/DBConfig.php";
include_once "../dtos/CalendarDto.php";
class CalendarService {
    private $pdo;
    public function __construct() {
        $this -> pdo = DBConfig::getConnection();
    }

    public function getList(int $user_id):array {
        $query = $this -> pdo -> prepare("SELECT * FROM calendar WHERE created_by = :user_id");
        $query -> bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $query -> execute();
       
        return $query -> fetchAll();
    }

    public function create(CalendarDto $calendar): bool {
        $query = $this -> pdo -> prepare("INSERT INTO calendar (id,name, description, start_date, end_date, created_by, status) 
        VALUES (:id,:name, :description, :start_date, :end_date, :created_by, :status)");
        $data = $calendar -> toArray();
        $query -> execute($data);
        return $query -> rowCount() > 0;
    }
}
