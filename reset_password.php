<?php
require 'config.php';

$token = $_GET['token'] ?? '';
$valid_token = false;

if (!empty($token)) {
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires_at > NOW()");
        $stmt->execute([$token]);
        if ($stmt->fetch()) {
            $valid_token = true;
        }
    } catch (PDOException $e) {
        // Handle error silently here
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuthX - Create New Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Hamburger Menu -->
    <div class="hamburger-menu">
        <div class="hamburger-icon" id="hamburger-icon">
            <span></span><span></span><span></span>
        </div>
        <div class="dropdown-menu" id="dropdown-menu">
            <a href="index.php">Home / Login</a>
            <a href="forgot_password.php">Forgot Password</a>
        </div>
    </div>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="glass-panel">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!$valid_token): ?>
            <div class="alert alert-error">
                This password reset link is invalid or has expired.
            </div>
            <p class="toggle-link"><a href="forgot_password.php">Request a new link</a></p>
        <?php else: ?>
            <h2>New Password</h2>
            <p class="subtitle">Enter your new secure password</p>
            <form action="reset_action.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="input-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                </div>
                <button type="submit" class="btn">Update Password</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="footer">
        &copy; <?php echo date("Y"); ?> All rights reserved | Rajneesh Chaubey
    </div>
</body>
</html>
