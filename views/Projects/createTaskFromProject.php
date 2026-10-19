<h2>Create Task</h2>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
<form id="create_task_form" onsubmit="getContent({url:'projects/createTaskFromProject/<?php echo $data['id']?>',target:'projects_content',data:new FormData(this)});return false;">
<div class="input-group">
    <span class="input-group-text">Epic</span>
    <select name="epic_id" id="epic_id">
    <?php 
    foreach($data["epics"] as $epic){
        echo $option = '<option value="'.$epic['id'].'">'.$epic['name'].'</option>';
    }
    
    ?>		

    </select>
    </div>
    <div class="input-group">
        <span class="input-group-text">Name</span>
        <input type="text" name="name" id="name" required>
    </div>
    <div class="input-group">
        <span class="input-group-text">Description</span>
        <textarea name="description" id="description"></textarea>
    </div>
    <div class="input-group">
        <span class="input-group-text">Status</span>
        <select name="status_id" id="status_id">
        <?php 
        foreach($data["statuses"] as $status){
            echo $option = '<option value="'.$status['id'].'">'.$status['name'].'</option>';
        }
        
        ?>		

        </select>
        </div>
    <div class="input-group">
        <span class="input-group-text">Assigned To</span>
        <select name="asigned_to" id="asigned_to">
        <?php
        foreach($data["members"] as $member){
                echo $option = '<option value="'.$member['id'].'">'.$member['username'].'</option>';
            }
            
            ?>
        </select>
    </div>
    <div class="input-group">
        <button type="submit">Create Task</button>
    </div>
</form>

            </div>
        </div>
    </div>