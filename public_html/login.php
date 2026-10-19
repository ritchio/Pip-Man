<?php
session_start();
require_once __DIR__ . '/../services/UserService.php';
$message = '';

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === '' || $password === '') {
        $message = 'Please enter both username and password.';
    } else {
        $user_service = new UserService();
        $user_id = $user_service->authenticateUser($username, $password);
        if ($user_id > 0) {
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            $_SESSION["user_id"] = $user_id;
            $_SESSION['permissions'] = $user_service->getPermissionsForUser($user_id);
            header('Location: index.php');
            exit;
        } else {
            $message = 'Invalid username or password.';
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
    <title>Login</title>

</head>
<body>
    <div class="card mx-auto mt-4">
        <div class="card-header">Login</div>
        <div class="card-body">
        <?php if ($message !== ''): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="input-group">
                <span for="username" class="input-group-text">Username</span>
                <input type="text" id="username" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required>
            </div>
            <div class="input-group">
                <span for="password" class="input-group-text">Password</span>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Sign In</button>
            <a href="register.php" class="button">Register</a>
        </form>
        </div>
    </div>
</body>
</html>
