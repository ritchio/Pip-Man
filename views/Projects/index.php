<h1>Projects</h1>
<ul class="title-menu">
    <li><a href="projects" target="content">Board</a></li>
    <li><a href="projects/manage" target="projects_content">Manage projects</a></li>
    <li><a href="projects/manageStatus" target="projects_content">Manage status</a></li>
    <li><a href="projects/create" target="projects_content">Create</a></li>
</ul>

<div id="projects_content">
    
    <?php 

    if($data != null){
        
        foreach($data as $project){

            echo '<h2 class="mt-4">'.$project["name"].'</h2>';
            echo '<ul class="title-menu">
            <li><a href="projects/createEpic/'.$project['id'].'" target="projects_content">Create Epic</a></li>
            <li><a href="projects/createTaskFromProject/'.$project['id'].'" target="projects_content">Create Task</a></li>
            <li><a href="#" onclick="toggleDiv(\'pcard_'.$project["id"].'\')">Toggle</a></li>
            <li><a href="projects/archive/'.$project['id'].'" data-confirm="Are you sure you want to archive this project?" target="content">Archive</a></li>
            </ul>';
            echo '<div class="row" id="pcard_'.$project["id"].'">';
            $html_options = '<option value="0">Change status</option>';
            foreach($project["statuses"] as $option){
                $html_options .= '<option value="'.$option["id"].'">'.$option["name"].'</option>';
            }

            $column_size = floor(12/count($project["statuses"]));
            foreach($project["statuses"] as $status){
                echo '<div class="col-'.$column_size.'"><h3 class="text-center">'.$status["name"].'</h3>';
                if(array_key_exists($status["id"],$project["tasks"]))
                    {
                        foreach($project["tasks"][$status["id"]] as $task){
                        ?>
                        
                            <div class="card" id="tcard_<?php echo $task['id'] ?>">
                                <div class="card-header bg-secondary"><?php echo $task['epic'].': '.$task["name"]?></div>
                                <div class="card-body" style="position:relative;">
                                    <p><?php echo $task["description"]?></p>
                                </div>
                                <div class="card-footer">
                                    <div class="input-group">
                                    <span class="input-group-text no-wrap">Assigned to</span>
                                    <select id="asigned_to_<?php echo $task['id']?>" onchange="getContent({url:'projects/asignTask/<?php echo $task['id']?>/'+this.value, target:'content'})">
                                            <option value="0">Unassigned</option>
                                            <?php
                                                foreach($project["members"] as $member){
                                                    echo '<option value="'.$member["id"].'" '.($member["id"]==$task["asigned_to"]?"selected":"").'>'.$member["username"].'</option>';
                                                }
                                            ?>
                                            </select>

                                    </div>    
                                    <div class="row">
                                        <div class="col-9">
                                            <select onchange="changeTaskStatus(this.value,<?php echo $task['id']?>)"><?php echo $html_options ?></select>
                                        </div>
                                        <div class="col-3">
                                            <button class="w-100" onclick="getContent({url:'projects/editTask/<?php echo $task['id']?>',target:'modal'})">Edit</button>
                                        </div>
                                    </div>
                            </div>
                            </div>
                        <?php
                        }

                    }
                
                
                echo '</div>';
            }
            ?>
            </div>
            
            <?php
        }
    }
?>
</div>
<script>
function changeTaskStatus(status_id,task_id){
    if(status_id != 0){
        getContent({
                url:`projects/changeTaskStatus/${task_id}/${status_id}`,
                target:"content"
            });
    }
    
}
    </script>