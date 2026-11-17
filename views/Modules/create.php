<h1>Modules</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>" target="content">Back</a></li>
</ul>

<div class="card">
    <div class="card-header">
        <h2>Create Module</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo $this->controller_url; ?>/create" id="createModuleForm" target="content">
            <div class="input-group">
                <span class="input-group-text">Name:</span>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="input-group">
                <span class="input-group-text">URL:</span>
                <input type="text" name="url" id="url" class="form-control" required>
            </div>  
            <div class="input-group">
                <span class="input-group-text">Restricted:</span>
                <select name="restricted" id="restricted" class="form-control" required>
                    <option value="">Select</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="input-group">
                <span class="input-group-text">Active:</span>
                <select name="active" id="active" class="form-control" required>
                    <option value="">Select</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="input-group">
                <span class="input-group-text">List Order:</span>
                <input type="number" name="list_order" id="list_order" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
