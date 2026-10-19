<form method="post" action="Projects/ImportTasks/<?php echo $data["project_id"]?>" enctype="multipart/form-data" target="projects_content" id="import_json">
    <div class="input-group">
        <div class="input-group-text">JSON file</div>
        <input type="file" name="json_file" accept=".json, application/json">
        <input type="submit" value="Import">
    </div>
</form>