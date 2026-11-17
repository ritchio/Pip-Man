<h1>Users</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>" target="content">Back</a></li>
</ul>

<div class="card">
    <div class="card-header">
        <h2>Create User</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo $this->controller_url; ?>/create" id="createUserForm" target="content">
            <div class="input-group">
                <span class="input-group-text">Username:</span>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="input-group">
                <span class="input-group-text">Password:</span>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>
