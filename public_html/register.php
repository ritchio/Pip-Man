<?php
session_start();
require_once __DIR__ . '/services/UserService.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $message = 'Please provide both username and password.';
    } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $userService = new UserService();
            if ($userService->registerUser($username, $password)) {
                $message = 'Registration successful.';
            } else {
                $message = 'Registration failed.';
            }
       
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/default.css" media="screen" rel="stylesheet" type="text/css">
    <title>Register</title>

</head>
<body>
    <div class="card mx-auto mt-4">
        <div class="card-header">Register</div>
        <div class="card-body">
        <?php if ($message !== ''): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post" action="register.php">
            <div class="input-group">
                <span for="username" class="input-group-text">Username</span>
                <input type="text" id="username" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required>
            </div>
            <div class="input-group">
                <span for="password" class="input-group-text">Password</span>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Create your account</button>
            <a href="login.php" class="button">Login</a>
        </form>
        </div>
    </div>
</body>
</html>
