<h2>Tasks</h2>

    <div class="input-group">
        <span class="input-group-text">Status</span>
        <select name="status_id" id="status_id" onchange="getContent({url:'projects/tasksByStatus/'+this.value+'/'+document.getElementById('project_id').value,target:'tasks_content'})">
		<?php 
		$html_options = "";
		foreach($data["statuses"] as $status){
			
			echo $option = '<option value="'.$status['id'].'" '.($status['id']==$data["selected_status"]?"selected":"").'>'.$status['name'].'</option>';
			$html_options .= $option;
		}
		
		?>		

		</select>
	</div>
	<div class="input-group">
        <span class="input-group-text">Project</span>
        <select name="project_id" id="project_id" onchange="getContent({url:'projects/tasksByStatus/'+document.getElementById('status_id').value+'/'+this.value,target:'tasks_content'})">
		<?php 
		echo "<option value='0'>All</option>";
		foreach($data["projects"] as $project){
			echo $option = '<option value="'.$project['id'].'" '.($project['id']==$data["selected_project"]?"selected":"").'>'.$project['name'].'</option>';
		}
		
		?>		

		</select>
	</div>
<?php
if($data["tasks"] != null)
{
	echo '<table>';
	foreach($data["tasks"] as $task){
		?>
		<div class="card" id="tcard_<?php echo $task['id'] ?>">
			<div class="card-header bg-secondary"><?php echo $task["project"]." - ".$task["epic"]." - ".$task["name"]?></div>
			<div class="card-body"><?php echo nl2br($task["description"])?>
			<p>Assigned to: <?php echo $task["assigned_user"]?></p>
		</div>
			<?php 
			if($task["asigned_to"] == $_SESSION["user_id"] || $task["asigned_to"] == 0){?>
				<div class="card-footer">

					<select onchange="getContent({url:'projects/changeTaskStatus/<?php echo $task['id']?>/'+this.value, target:'tasks_content',data:{redirect_to_action:'tasksByStatus',status_id:'<?php echo $data['selected_status']?>',project_id:'<?php echo $data['selected_project']?>'}})">
						<?php echo $html_options ?>
					</select>
				</div>

			<?php
			}
				?>
		</div>
		<?php
	}
	echo '</table>';
}else{
	echo 'No tasks found';
}
