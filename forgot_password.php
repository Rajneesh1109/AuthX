<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuthX - Forgot Password</title>
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

        <?php if (isset($_SESSION['success_html'])): ?>
            <!-- Simulating an email sent to the user -->
            <div class="alert alert-success" style="word-break: break-all;">
                <?php echo $_SESSION['success_html']; unset($_SESSION['success_html']); ?>
            </div>
            <p class="toggle-link"><a href="index.php">Return to Login</a></p>
        <?php else: ?>
            <h2>Reset Password</h2>
            <p class="subtitle">Enter your email to receive a reset link</p>
            <form action="forgot_action.php" method="POST">
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="btn">Send Reset Link</button>
            </form>
            <p class="toggle-link"><a href="index.php">Back to Login</a></p>
        <?php endif; ?>
    </div>

</body>
</html>
