<?php
session_start();
require_once 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $condicion = trim($_POST['condicion']); // estudiante o docente
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Convertimos el rol en número según tu lógica de roles
    $rol_id = ($condicion === 'estudiante') ? 1 : (($condicion === 'docente') ? 2 : 0);

    if ($rol_id === 0) {
        echo "<script>alert('Condición inválida.'); window.location.href='campus.html';</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT id, nombre, email, password, rol_id FROM usuarios WHERE email = ? AND rol_id = ?");
    $stmt->bind_param("si", $email, $rol_id);
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
        echo "<script>alert('Usuario no encontrado para esa condición.'); window.location.href='campus.html';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: campus.html");
    exit();
}
?>