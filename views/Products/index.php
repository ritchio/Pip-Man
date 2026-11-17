<h1>Products</h1>
<ul class="title-menu">
    <li><a href="<?php echo $this->controller_url; ?>/create" target="content">Create</a></li>
</ul>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)) : ?>
            <?php foreach ($data as $product) : ?>
                <tr>
                    <td><?php echo htmlspecialchars((string)$product['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo ((int)$product['active'] === 1) ? 'Yes' : 'No'; ?></td>
                    <td>
                        <a href="<?php echo $this->controller_url; ?>/edit/<?php echo (int)$product['id']; ?>" target="content" class="button">Edit</a>
                        <a href="<?php echo $this->controller_url; ?>/delete/<?php echo (int)$product['id']; ?>" target="content" class="button" data-confirm="Are you sure you want to delete this product?">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4">No products found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
