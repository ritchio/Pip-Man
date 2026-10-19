<?php
include_once("../system/Controller.php");
include_once("../services/NotesService.php");

class NotesController extends Controller{
    private $notesService;
    public function __construct(){
        $this ->notesService = new NotesService();
    }

    public function index(int $only_active=1){
        $notes = $this->notesService -> getList($_SESSION["user_id"], ($only_active == 1));
        $data = ["notes"=>$notes,"selected"=>$only_active];
        return $this -> view(data:$data);
    }

      public function edit(int $id) {
       if($id != null && $id > 0){
            $note = $this -> notesService -> getById($id);
       }else{
        return $this -> notFound();
       }
    }

    public function create($data=null) {
        if($data != null)
        {
            $note = $this -> notesService -> create($data);
    
            return $this -> index();
            
        }

        return $this -> index();
    }

    public function delete($id=null){
        if($id != null && $id > 0){
            $this -> notesService -> delete($id);
        }

        return $this -> index();
    }
    
    public function close($id = null){
        if($id != null && $id > 0){
            $this -> notesService -> deactivate($id);
        }
        return  $this -> index();
    }
}