<?php
/**
 * Test version - minimal dashboard
 */

session_start();
$correct_password = 'artist2025';

if (isset($_POST['password'])) {
    if ($_POST['password'] === $correct_password) {
        $_SESSION['authenticated'] = true;
    } else {
        $error_message = 'Incorrect password';
    }
}

$is_authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Dashboard</title>
</head>
<body>
    <?php if (!$is_authenticated): ?>
        <form method="post">
            <input type="password" name="password" placeholder="Enter password">
            <button type="submit">Login</button>
        </form>
    <?php else: ?>
        <h1>SUCCESS! Dashboard working!</h1>
        <a href="?logout">Logout</a>
    <?php endif; ?>
</body>
</html>