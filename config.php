<?php
// config.php
session_start();

// Default local XAMPP configuration
$host = 'localhost';
$dbname = 'authx_db';
$username = 'root';
$password = '';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

// Override with Render PostgreSQL if DATABASE_URL is set
if (getenv('DATABASE_URL')) {
    $db_url = parse_url(getenv('DATABASE_URL'));
    $host = $db_url['host'];
    $port = $db_url['port'] ?? 5432;
    $dbname = ltrim($db_url['path'], '/');
    $username = $db_url['user'];
    $password = $db_url['pass'];
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
}

try {
    if (getenv('DATABASE_URL')) {
        $pdo = new PDO($dsn, $username, $password);
    } else {
        $pdo = new PDO($dsn, $username, $password);
    }
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Fetch objects by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle Auto-login with Remember Me Cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    try {
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE remember_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        } else {
            // Invalid token, clear the cookie
            setcookie('remember_token', '', time() - 3600, "/");
        }
    } catch (PDOException $e) {
        // Silently fail auto-login on db error
    }
}
?>
