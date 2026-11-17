<h1>Clients</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>" target="content">Back</a></li>
</ul>

<div class="card">
    <div class="card-header">
        <h2>Edit Client</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo $this->controller_url; ?>/edit/<?php echo (int)$data['id']; ?>" id="editClientForm" target="content">
            <div class="input-group">
                <span class="input-group-text">Name:</span>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="input-group">
                <span class="input-group-text">Last name:</span>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($data['last_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="input-group">
                <span class="input-group-text">Phone number:</span>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo htmlspecialchars($data['phone_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="input-group">
                <span class="input-group-text">Social link:</span>
                <input type="url" name="social_link" id="social_link" class="form-control" value="<?php echo htmlspecialchars($data['social_link'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
