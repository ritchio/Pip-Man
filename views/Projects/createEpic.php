<div class="row">
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">Create Epic</div>
            <div class="card-body">
                <form class="col-6" target="content" action="projects/createEpic/<?php echo $data["id"] ?>" method="post">
                <div class="input-group">
                    <span class="input-group-text">Name</span>
                    <input type="text" name="name" id="epic_name">
                </div>
                <div class="input-group">
                    <span class="input-group-text">Description</span>
                    <textarea name="description" id="epic_description"></textarea>
                </div>
                <input type="submit" class="button" value="Save">
                </form>
            </div>
        </div>
    </div>
</div>
