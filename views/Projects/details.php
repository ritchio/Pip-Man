<h2><?php echo $data['name']?></h2>
<ul class="title-menu">
	<li><a href="projects/createEpic/<?php echo $data["id"];?>" target="projects_content">Create epic</a></li>
	<li><a href="projects/importTasks/<?php echo $data["id"];?>" target="projects_content">Import tasks</a></li>
</ul>

<?php
if($data ["epics"]!= null){
	foreach($data['epics'] as $epic){
		echo '<h3>'.$epic["name"].'</h3>';
		echo '<ul class="title-menu"><span>'.$epic["description"].'</span><li><a href="projects/createTask/'.$epic["id"].'" target="epic_'.$epic["id"].'_content">Create task</a></li></ul>';

		echo '<div id="epic_'.$epic["id"].'_content" class="row">';
		$this -> view(file_name:"tasks.php",data:$epic["tasks"]);
		echo '</div>';
	}
}


?>

