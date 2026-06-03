<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires_at > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update password and clear reset token
            $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires_at = NULL WHERE id = ?");
            $stmt->execute([$hashed_password, $user['id']]);

            $_SESSION['success'] = "Password has been successfully reset! You can now log in.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid or expired token.";
            header("Location: reset_password.php?token=" . urlencode($token));
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "System error during password reset.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
