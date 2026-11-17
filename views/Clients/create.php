<h1>Clients</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>" target="content">Back</a></li>
</ul>

<div class="card">
    <div class="card-header">
        <h2>Create Client</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo $this->controller_url; ?>/create" id="createClientForm" target="content">
            <div class="input-group">
                <span class="input-group-text">Name:</span>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="input-group">
                <span class="input-group-text">Last name:</span>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
            </div>
            <div class="input-group">
                <span class="input-group-text">Phone number:</span>
                <input type="text" name="phone_number" id="phone_number" class="form-control">
            </div>
            <div class="input-group">
                <span class="input-group-text">Social link:</span>
                <input type="url" name="social_link" id="social_link" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>
