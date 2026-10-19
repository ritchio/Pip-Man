<?php
include_once("../system/Controller.php");
include_once("../services/NotesService.php");

class HomeController extends Controller{
    private $notesService;
        public function __construct(){
        $this ->notesService = new NotesService();
    }

     public function index(){

        return $this -> view();
    }

}