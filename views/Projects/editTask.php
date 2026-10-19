<div class="card">
    <div class="card-header bg-secondary">Edit Task</div>
    <div class="card-body">
        <?php
        if($message != null){
            echo $message;
        }?>
        <form class="col-6" target="modal" action="projects/editTask/<?php echo $data["id"] ?>" method="post">
            <div class="input-group">
                <span class="input-group-text">Name</span>
                <input type="text" name="name" id="task_name" value="<?php echo $data["name"]?>">
            </div>
            <div class="input-group">
                <span class="input-group-text">Description</span>
                <textarea name="description" id="task_description"><?php echo $data["description"]?></textarea>
            </div>
            <input type="submit" class="button" value="Save">
        </form>
    </div>
</div>
