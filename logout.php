<?php
session_start();
session_destroy(); // Destroy the session
header("Location: admin.php?logged_out=1"); // Redirect to the login page with a message
exit;
?>
