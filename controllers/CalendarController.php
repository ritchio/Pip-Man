<?php
include_once ("../services/CalendarService.php");
include_once ("../system/Controller.php");
include_once ("../dtos/CalendarDto.php");

class CalendarController extends Controller {
    private $calendarService;

    public function __construct() {
        $this->calendarService = new CalendarService();
    }

    public function index() {
        try {
            $calendars = $this->calendarService->getList($_SESSION["user_id"]);
            return $this->view(data: $calendars);
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    public function today() {
        try {
            $today_end = date("Y-m-d 23:59:59"); 
            $today_start = date("Y-m-d 00:00:00");
            $calendars = $this->calendarService->getList($_SESSION["user_id"]);
            $todayEvents = array_filter($calendars, function ($event) use ($today_start,$today_end) {
                return $event["start_date"] > $today_start && $event["start_date"] <= $today_end;
            });
            $upcomingEvents = array_filter($calendars, function ($event) use ($today_end) {
                return $event["start_date"] > $today_end ;
            });
            return $this->view(data: ["todayEvents" => $todayEvents, "upcomingEvents" => $upcomingEvents]);
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function create($data = null) {
        if ($data != null) {
            $calendarDto = CalendarDto::fromArray($data);
            
            $result = $this->calendarService->create($calendarDto);
            if ($result) {
                return $this->view(message: "<p class=\"bg-success p-3 rounded\">Event created successfully</p>", success: true);
            } else {
                return $this->view(message: "<p class=\"bg-danger p-3 rounded\">Failed to create calendar</p>", success: false);
            }
        } else {
            return $this->view();
        }
        return $this->view();
    }
}   
