
<div class="card">
    <div class="card-header">Create Project</div>
    <div class="card-body">

        <form class="col-6" target="content" action="projects/create" method="post">
            <div class="input-group">
                <span for="project_name" class="input-group-text">Name</span>
                <input type="text" name="name" id="project_name">
            </div>
            <div class="input-group">
                <span for="project_description" class="input-group-text">Description</span>
                <textarea name="description" id="project_description"></textarea>
            </div>
            <input type="submit" class="button" value="Save">
        </form>
    </div>
</div>