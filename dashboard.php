<?php
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data (Read)
try {
    $stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // User not found in db, destroy session
        session_destroy();
        header("Location: index.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error fetching user data.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuthX - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="glass-panel dashboard-container">
        <div class="profile-header">
            <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
            <a href="logout.php" class="btn" style="width: auto; padding: 0.5rem 1rem; margin-top: 0;">Logout</a>
        </div>

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

        <div class="user-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Joined:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($user['created_at']))); ?></p>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 2rem 0;">

        <h3>Update Profile</h3>
        <p class="subtitle" style="text-align: left; margin-bottom: 1rem;">Update your email or password</p>
        <form action="update_profile.php" method="POST">
            <div class="input-group">
                <label for="update-email">New Email Address</label>
                <input type="email" id="update-email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="input-group">
                <label for="update-password">New Password (leave blank to keep current)</label>
                <input type="password" id="update-password" name="password" minlength="8">
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>

        <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 2rem 0;">
        
        <h3>Danger Zone</h3>
        <form action="delete_account.php" method="POST" id="delete-form">
            <button type="submit" class="btn btn-danger">Delete Account</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>
