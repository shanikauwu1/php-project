

<?php
require 'config.php';
session_start();

// Ensure session timeout is set
if (!isset($_SESSION['username']) || !isset($_SESSION['timeout']) || time() > $_SESSION['timeout']) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy session
    header("Location: login.php");
    exit();
}

// Update session timeout on user activity
$_SESSION['timeout'] = time() + TIMEOUT_IN_SECONDS;


if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) { 
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
?>
