<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header("Location: forgot_password.php");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate a secure random token
            $token = bin2hex(random_bytes(32));
            $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Store the token in the database
            $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires_at = ? WHERE id = ?");
            $stmt->execute([$token, $expires_at, $user['id']]);

            // Simulate sending an email by showing the link on screen
            $reset_link = "http://localhost/AuthX/reset_password.php?token=" . $token;
            
            $_SESSION['success_html'] = "<strong>Email Sent (Simulated)!</strong><br>Click this link to reset your password:<br><br><a href='$reset_link' style='color:#fff; text-decoration:underline;'>$reset_link</a><br><br><em>(Link expires in 1 hour)</em>";
        } else {
            // Even if the email doesn't exist, we show a generic message to prevent email enumeration
            $_SESSION['success_html'] = "If that email exists in our system, a reset link has been sent.";
        }
        
        header("Location: forgot_password.php");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    header("Location: forgot_password.php");
    exit();
}
?>
