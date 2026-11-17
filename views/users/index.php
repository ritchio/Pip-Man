<?php
// views/users/list.php
<h1>All Users</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td>
                <a href="/users/show/<?= $user['id'] ?>">View</a> |
                <a href="/users/edit/<?= $user['id'] ?>">Edit</a> |
                <a href="/users/delete/<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>