var pomodoro_status = parseInt(sessionStorage.getItem("pomodoro_status"));//0 paused, 1 focus, 2 rest
var pomodoro_last_status = parseInt(sessionStorage.getItem("pomodoro_last_status"));;
var pomodoro_control_button = null;
var pomodoro_total_time = parseInt(sessionStorage.getItem("pomodoro_total_time"));
var pomodoro_focus_time = parseInt(sessionStorage.getItem("pomodoro_focus_time"));
var pomodoro_current_time = parseInt(sessionStorage.getItem("pomodoro_current_time"));


function pomodoroControl(auto = false){
    let pomodoro_timer = document.getElementById("pomodoro_timer");
    let pomodoro_control_button = document.getElementById("pomodoro_control_button");
    let pomodoro_total_focus = document.getElementById("pomodoro_total_focus");
    let pomodoro_total = document.getElementById("pomodoro_total");
    
    if(Number.isNaN(pomodoro_focus_time)){
        pomodoro_focus_time = 0;
        sessionStorage.setItem("pomodoro_focus_time","0");
    }
    if(Number.isNaN(pomodoro_total_time)){
        pomodoro_total_time = 0;
        sessionStorage.setItem("pomodoro_total_time","0");
    }
    if(Number.isNaN(pomodoro_current_time)){
        pomodoro_current_time = 0;
        sessionStorage.setItem("pomodoro_current_time","0");
    }

    if(((Number.isNaN(pomodoro_status) || pomodoro_status == 0) && auto==false) || (auto ==true && pomodoro_status >0)){
        pomodoro_control_button.innerText = "Pause";
        pomodoro_status = Number.isNaN(pomodoro_last_status)? 1 : pomodoro_last_status;
        pomodoroSetStatus();
        console.log("se inicia intervalo");
        if(typeof window.pomodoro_interval !== "undefined"  && window.pomodoro_interval > 0){
            clearInterval(window.pomodoro_interval);
        }
        let current_hours = Math.floor(pomodoro_current_time / 60);
        let current_minutes = pomodoro_current_time %60;
        pomodoro_timer.innerText = String(current_hours).padStart(2,"0") + ':' + String(current_minutes).padStart(2,'0');

        window.pomodoro_interval = setInterval(function(){
            pomodoro_total_time += 1;
            let total_hours = Math.floor(pomodoro_total_time / 60);
            let total_minutes = pomodoro_total_time %60;
            sessionStorage.setItem("pomodoro_total_time",String(pomodoro_total_time));
            pomodoro_total.innerText = String(total_hours).padStart(2,"0") + ':' + String(total_minutes).padStart(2,'0');

            if(pomodoro_status == 1){
                pomodoro_focus_time += 1;
                let focus_hours = Math.floor(pomodoro_focus_time / 60);
                let focus_minutes = pomodoro_focus_time %60;
                sessionStorage.setItem("pomodoro_focus_time",String(pomodoro_focus_time));
                pomodoro_total_focus.innerText = String(focus_hours).padStart(2,"0") + ':' + String(focus_minutes).padStart(2,'0');
            }
            pomodoro_current_time += 1;
            current_hours = Math.floor(pomodoro_current_time / 60);
            current_minutes = pomodoro_current_time %60;
            sessionStorage.setItem("pomodoro_current_time",String(pomodoro_current_time));
            pomodoro_timer.innerText = String(current_hours).padStart(2,"0") + ':' + String(current_minutes).padStart(2,'0');
        },60000); //we dont need to update the timer every second, every minute is enough
        
    }else{
        console.log("se detiene intervalo");
        pomodoro_status = 0;
        clearInterval(window.pomodoro_interval);

        pomodoro_control_button.innerText = "Continue";
    }

        sessionStorage.setItem("pomodoro_status",String(pomodoro_status));

}

function pomodoroChangeStatus(){
    
    sessionStorage.setItem("pomodoro_last_status",String(pomodoro_status));

    if(pomodoro_status == 1){
        pomodoro_status = 2;
    }else{
        pomodoro_status = pomodoro_status == 0? 0 : 1;
    }

    pomodoro_last_status = pomodoro_last_status == 2? 1 : 2;
    pomodoro_current_time = 0;
    pomodoroSetStatus();
    sessionStorage.setItem("pomodoro_current_time",String(pomodoro_current_time));
    sessionStorage.setItem("pomodoro_status",String(pomodoro_status));
    sessionStorage.setItem("pomodoro_last_status",String(pomodoro_last_status));
}

function pomodoroSetStatus(){
    let pomodoro_status_button = document.getElementById("pomodoro_status_button");
    let pomodoro_status_div = document.getElementById("pomodoro_status");
    let pomodoro_timer = document.getElementById("pomodoro_timer");

    if(pomodoro_last_status == 1){
        pomodoro_status_button.innerText = "Rest"
        pomodoro_status_div.innerText = "Focusing";
    }else{
        pomodoro_status_button.innerText = "Focus";
        pomodoro_status_div.innerText = "Resting";
    }

    pomodoro_timer.innerText = "--:--";
}