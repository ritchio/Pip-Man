<?php
if($data != null)
{
	
	foreach($data as $task){
		echo '<div class="card col-3" id="tcard_'.$task['id'].'">';
		echo '<div class="card-header bg-secondary">'.$task["name"].'</div>';
		echo '<div class="card-body">'.nl2br($task["description"]).'</div>';
		echo '<div class="card-footer"></div>';
		echo '</div>';	
	}
}else{
	echo 'No tasks found';
}
