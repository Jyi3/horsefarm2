<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('You must be logged in to access this website.')</script>";
        echo "<script>window.location = 'login_form.php'</script>";
        exit;
    } else {
        $now = time();
        if (isset($_SESSION['expire']) && $now > $_SESSION['expire']) {
            session_unset();
            session_destroy();
            echo "<script>alert('Your session has expired. Please log in again.')</script>";
            echo "<script>window.location = 'login_form.php'</script>";
            exit;
        }
        $_SESSION['expire'] = $now + (30 * 60); // Session expires after 30 minutes of inactivity
        if (isset($_SESSION['permissions'])) {
            $permission_level = $_SESSION['permissions'];
            echo "You are logged in with permission level $permission_level";
        } else {
            echo "Error: Permission level not found in session";
            exit;
        }
    }
}
?>
