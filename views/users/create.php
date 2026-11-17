<?php
// views/users/create.php
<h1>Create New User</h1>
<form action="/users" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br><br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br><br>
    <button type="submit">Create User</button>
</form>