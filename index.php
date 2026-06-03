<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuthX - Premium User Authentication</title>
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

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <div id="login-form-container">
            <h2>Welcome Back</h2>
            <p class="subtitle">Enter your credentials to access AuthX</p>
            <form action="login_action.php" method="POST" id="login-form">
                <div class="input-group">
                    <label for="login-email">Email Address</label>
                    <input type="email" id="login-email" name="email" required autocomplete="email">
                </div>
                <div class="input-group">
                    <label for="login-password" style="display: flex; justify-content: space-between;">
                        Password
                        <a href="forgot_password.php" style="color: var(--primary); text-decoration: none;">Forgot?</a>
                    </label>
                    <input type="password" id="login-password" name="password" required autocomplete="current-password">
                </div>
                <div class="input-group" style="display: flex; align-items: center; margin-bottom: 1rem;">
                    <input type="checkbox" id="remember" name="remember" style="width: auto; margin-right: 0.5rem; accent-color: var(--primary);">
                    <label for="remember" style="margin-bottom: 0;">Remember me</label>
                </div>
                <button type="submit" class="btn">Sign In</button>
            </form>
            <p class="toggle-link">Don't have an account? <a href="#" id="show-register">Register here</a></p>
        </div>

        <!-- Register Form (Hidden by default) -->
        <div id="register-form-container" class="hidden">
            <h2>Create Account</h2>
            <p class="subtitle">Join AuthX today</p>
            <form action="register_action.php" method="POST" id="register-form">
                <div class="input-group">
                    <label for="reg-username">Username</label>
                    <input type="text" id="reg-username" name="username" required autocomplete="username" minlength="3">
                </div>
                <div class="input-group">
                    <label for="reg-email">Email Address</label>
                    <input type="email" id="reg-email" name="email" required autocomplete="email">
                </div>
                <div class="input-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" required minlength="8" autocomplete="new-password">
                </div>
                <div class="input-group">
                    <label for="reg-confirm">Confirm Password</label>
                    <input type="password" id="reg-confirm" name="confirm_password" required minlength="8" autocomplete="new-password">
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <p class="toggle-link">Already have an account? <a href="#" id="show-login">Sign In</a></p>
        </div>
    </div>
    <div class="footer">
        &copy; <?php echo date("Y"); ?> All rights reserved | Rajneesh Chaubey
    </div>
    <script src="script.js"></script>
</body>
</html>
