<?php
session_start();

// Agar admin session set hai toh destroy karein
if(isset($_SESSION['id'])) {
    session_unset();  // Sab session variables clear karega
    session_destroy(); // Puri session destroy karega
}

// Admin ko admin login page par redirect karein
header("Location: ../home.php");
exit;
?>
