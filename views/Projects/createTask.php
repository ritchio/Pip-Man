<div class="card">
    <div class="card-header bg-secondary">Create Task</div>
    <div class="card-body">
        <form class="col-6" target="epic_<?php echo $data["id"]?>_content" action="projects/createTask/<?php echo $data["id"] ?>" method="post">
            <div class="input-group">
                <label for="epic_name">Name</label>
                <input type="text" name="name" id="epic_name">
            </div>
            <div class="input-group">
                <label for="epic_description">Description</label>
                <textarea name="description" id="epic_description"></textarea>
            </div>
            <input type="submit" class="button" value="Save">
        </form>
    </div>
</div>

