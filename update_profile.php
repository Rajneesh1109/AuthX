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
    $bio = trim($_POST['bio'] ?? '');
    $profile_pic = null;

    if (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: dashboard.php");
        exit();
    }

    // Handle File Upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_pic']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = "user_" . $user_id . "_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads/" . $new_filename)) {
                $profile_pic = $new_filename;
            }
        } else {
            $_SESSION['error'] = "Invalid image format. Only JPG, PNG, and GIF are allowed.";
            header("Location: dashboard.php");
            exit();
        }
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

        // Build the update query dynamically
        $sql = "UPDATE users SET email = ?, bio = ?";
        $params = [$new_email, $bio];

        if (!empty($new_password)) {
            if (strlen($new_password) < 8) {
                $_SESSION['error'] = "Password must be at least 8 characters.";
                header("Location: dashboard.php");
                exit();
            }
            $sql .= ", password_hash = ?";
            $params[] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        if ($profile_pic) {
            $sql .= ", profile_pic = ?";
            $params[] = $profile_pic;
        }

        $sql .= " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

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
