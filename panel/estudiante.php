<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'panel/estudiante-dashboard.html';
?>
