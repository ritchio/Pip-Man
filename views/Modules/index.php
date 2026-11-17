<h1>Modules</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>/create" target="content">Create</a></li>
</ul>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>URL</th>
            <th>Restricted</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)) : ?>
            <?php foreach ($data as $module) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($module['id']); ?></td>
                    <td><?php echo htmlspecialchars($module['name']); ?></td>
                    <td><?php echo $module['url']; ?></td>
                    <td><?php echo ($module['restricted'] == 1) ? 'Yes' : 'No'; ?></td>
                    <td>
                        <a href="<?php echo $this->controller_url; ?>/edit/<?php echo $module['id']; ?>" target="content" class="button">Edit</a>
                        <a href="<?php echo $this->controller_url; ?>/delete/<?php echo $module['id']; ?>" target="content" class="button" data-confirm="Are you sure you want to delete this module?">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4">No modules found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
