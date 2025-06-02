<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 2) {
    header('Location: login.php');
    exit();
}
?>

<?php include 'panel/docente_dashboard.html'; ?>
