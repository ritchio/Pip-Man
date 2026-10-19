
<div class="row">
    <div class="col-4" id="notes_content"></div><!-- col notes -->

    <div class="col-4" id="tasks_content"></div><!-- col tasks -->

    <div class="col-4" >
       
        <h2>Calendar</h2>
        <div id="calendar_content">

        </div><!-- col calendar -->
    </div><!-- 3rd col -->
    

</div><!--row-->
<script>

    getContent({url:"projects/tasksByStatus/2",target:"tasks_content"});
    getContent({url:"notes",target:"notes_content"});
    getContent({url:"calendar/today",target:"calendar_content"});
    //pomodoroControl(true);



</script>