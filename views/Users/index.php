<h1>Users</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>/create" target="content">Create</a></li>
</ul>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)) : ?>
            <?php foreach ($data as $user) : ?>
                <tr>
                    <td><?php echo htmlspecialchars((string)$user['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="<?php echo $this->controller_url; ?>/edit/<?php echo (int)$user['id']; ?>" target="content" class="button">Edit</a>
                        <a href="<?php echo $this->controller_url; ?>/delete/<?php echo (int)$user['id']; ?>" target="content" class="button" data-confirm="Are you sure you want to delete this user?">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="3">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
