<?php
session_start();
require_once 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $rol_id = intval($_POST['rol_id']); // asumimos que el formulario también envía la condición

    // Buscar usuario y su rol asociado
    $stmt = $conn->prepare("
        SELECT u.id, u.nombre, u.apellido, u.email, u.password, ur.rol_id
        FROM usuarios u
        INNER JOIN usuario_roles ur ON u.id = ur.usuario_id
        WHERE u.email = ? AND ur.rol_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("si", $email, $rol_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'apellido' => $usuario['apellido'],
                'email' => $usuario['email'],
                'rol_id' => $usuario['rol_id'],
                'foto_perfil' => $usuario['foto_perfil'] ?? ''  // puede estar vacío
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
        echo "<script>alert('Usuario no encontrado con ese rol.'); window.location.href='campus.html';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: campus.html");
    exit();
}
?>
