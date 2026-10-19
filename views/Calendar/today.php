
<?php
    echo '<h3>Today\'s Events</h3>';

if($data["todayEvents"] != null) {
foreach($data["todayEvents"] as $calendar) {
    echo '<div class="card">';
    echo '<div class="card-header bg-warning">'.$calendar["name"].'</div>';
    echo '<div class="card-body">';
    echo '<p>'.$calendar["description"].'</p>';
    echo '<p class="fs-2">'.date("h:i A",strtotime($calendar["start_date"])).' - '.date("h:i A",strtotime($calendar["end_date"])).'</p>';
    echo '</div></div>';
    
}
} else {
    echo '<p>No events found.</p>';

}
    echo '<h3>Upcoming Events</h3>';

if($data["upcomingEvents"] != null) {
    foreach($data["upcomingEvents"] as $calendar) {
        echo '<div class="card">';
        echo '<div class="card-header bg-secondary">'.$calendar["name"].'</div>';
        echo '<div class="card-body">';
        echo '<p>'.$calendar["description"].'</p>';
        echo '<p >'.$calendar["start_date"].' - '.$calendar["end_date"].'</p>';
        echo '</div></div>';
    }
} else {
    echo '<p>No events found.</p>';

}?>
</div>