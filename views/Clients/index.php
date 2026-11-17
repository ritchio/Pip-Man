<h1>Clients</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>/create" target="content">Create</a></li>
</ul>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Last name</th>
            <th>Phone number</th>
            <th>Social link</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)) : ?>
            <?php foreach ($data as $client) : ?>
                <tr>
                    <td><?php echo htmlspecialchars((string)$client['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($client['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($client['last_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($client['phone_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($client['social_link'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="<?php echo $this->controller_url; ?>/edit/<?php echo (int)$client['id']; ?>" target="content" class="button">Edit</a>
                        <a href="<?php echo $this->controller_url; ?>/delete/<?php echo (int)$client['id']; ?>" target="content" class="button" data-confirm="Are you sure you want to delete this client?">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6">No clients found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
