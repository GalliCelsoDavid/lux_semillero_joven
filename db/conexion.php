<?php
$host = "localhost";
$usuario = "root";
$password = "12345";
$base = "galli_cursos_online";

$conn = new mysqli($host, $usuario, $password, $base);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
