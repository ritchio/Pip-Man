<h1>Users</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>" target="content">Back</a></li>
</ul>

<div class="card">
    <div class="card-header">
        <h2>Edit User</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo $this->controller_url; ?>/edit/<?php echo (int)$data['id']; ?>" id="editUserForm" target="content">
            <div class="input-group">
                <span class="input-group-text">Username:</span>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($data['username'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="input-group">
                <span class="input-group-text">New password (optional):</span>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
