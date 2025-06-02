<?php
session_start();
require_once 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, nombre, email, password, rol_id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email'],
                'rol_id' => $usuario['rol_id']
            ];

            if ($usuario['rol_id'] == 1) {
                header("Location: estudiante-dashboard.html");
            } elseif ($usuario['rol_id'] == 2) {
                header("Location: profesor-dashboard.html");
            } else {
                echo "<script>alert('Rol no válido.'); window.location.href='campus.html';</script>";
            }
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='campus.html';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado.'); window.location.href='campus.html';</script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: campus.html");
    exit();
}
?>