<?php
// views/users/show.php
<h1>User Details</h1>
<p>ID: <?= htmlspecialchars($user['id']) ?></p>
<p>Username: <?= htmlspecialchars($user['username']) ?></p>
<p>Password: [Hidden]</p>
<a href="/users/edit/<?= $user['id'] ?>">Edit</a> |
<a href="/users/delete/<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>