<h1>Calendar</h1>
<ul class="title-menu">
    <li><a href="calendar/create" target="modal">Create Event</a></li>
</ul>

<div class="row" id="calendar_content">
<?php
if($data != null) {
foreach($data as $calendar) {
    echo '<div class="card col-3">';
    echo '<div class="card-header bg-secondary">'.$calendar["name"].'</div>';
    echo '<div class="card-body">';
    echo '<p>'.$calendar["description"].'</p>';
    echo '<p>Start Date: '.$calendar["start_date"].'</p>';
    echo '<p>End Date: '.$calendar["end_date"].'</p>';
    echo '</div></div>';
}
} else {
    echo '<p>No events found.</p>';

}?>
</div>