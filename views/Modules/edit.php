<h1>Modules</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>" target="content">Back</a></li>
</ul>

<div class="card">
    <div class="card-header">
        <h2>Edit Module</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo $this->controller_url; ?>/edit/<?php echo (int)$data['id']; ?>" id="editModuleForm" target="content">
            <div class="input-group">
                <span class="input-group-text">Name:</span>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="input-group">
                <span class="input-group-text">Restricted:</span>
                <select name="restricted" id="restricted" class="form-control" required>
                    <option value="1" <?php echo ((int)$data['restricted'] === 1) ? 'selected' : ''; ?>>Yes</option>
                    <option value="0" <?php echo ((int)$data['restricted'] === 0) ? 'selected' : ''; ?>>No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
