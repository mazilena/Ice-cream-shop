<?php
session_start();

// Agar user session set hai toh destroy karein
if(isset($_SESSION['user_id'])) {
    session_unset();  // Sab session variables clear karega
    session_destroy(); // Puri session destroy karega
}

// User ko homepage ya login page par redirect karein
header("Location: ../login.php");
exit;
?>
