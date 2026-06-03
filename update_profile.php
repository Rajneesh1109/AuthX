<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $new_email = trim($_POST['email'] ?? '');
    $new_password = $_POST['password'] ?? '';

    if (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: dashboard.php");
        exit();
    }

    try {
        // Check if email is taken by another user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$new_email, $user_id]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Email is already in use by another account.";
            header("Location: dashboard.php");
            exit();
        }

        if (!empty($new_password)) {
            if (strlen($new_password) < 8) {
                $_SESSION['error'] = "Password must be at least 8 characters.";
                header("Location: dashboard.php");
                exit();
            }
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET email = ?, password_hash = ? WHERE id = ?");
            $stmt->execute([$new_email, $hashed_password, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$new_email, $user_id]);
        }

        $_SESSION['success'] = "Profile updated successfully.";
        header("Location: dashboard.php");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "An error occurred while updating the profile.";
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
