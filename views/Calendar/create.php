<div class="card">
    <div class="card-header bg-secondary">Create Event</div>
    <div class="card-body">
        <?php if($message != null){
            echo $message;
        }?>
        <form class="col-6" target="modal" action="calendar/create" method="post">
            <div class="input-group">
                <span class="input-group-text">Name</span>
                <input type="text" name="name" id="event_name">
            </div>
            <div class="input-group">
                <span class="input-group-text">Description</span>
                <textarea name="description" id="event_description"></textarea>
            </div>
            <div class="input-group">
                <span class="input-group-text">Start Date</span>
                <input type="datetime-local" name="start_date" id="event_start_date">
            </div>
            <div class="input-group">
                <span class="input-group-text">End Date</span>
                <input type="datetime-local" name="end_date" id="event_end_date">
            </div>

            <input type="submit" class="button" value="Save">
        </form>
    </div>
    <script>
        // let now  = new Date();
        document.getElementById("event_start_date").value = new Date().toISOString().slice(0, 16);
        // now.setHours(now.getHours() + 1);
        document.getElementById("event_end_date").value = new Date(new Date().setHours(new Date().getHours() + 1)).toISOString().slice(0, 16);
    </script>
    <?php if($success){
            ?>
            <script>
        getContent({url:"calendar", target:"content"});
        </script>
            <?php } ?>
</div>