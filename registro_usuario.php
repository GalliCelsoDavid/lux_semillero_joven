<?php
require_once 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $celular = trim($_POST['celular']);
    $rol_id = intval($_POST['rol_id']);
    $redes_sociales = trim($_POST['redes_sociales']);

    // Verificar si el correo ya está registrado con ese rol
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND rol_id = ?");
$stmt->bind_param("si", $email, $rol_id);  // 's' para string (email), 'i' para integer (rol_id)
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('El correo ya está registrado con ese rol.'); window.history.back();</script>";
    exit();
}


    $stmt->close();

    // Insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, password, celular, rol_id, redes_sociales) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $nombre, $apellido, $email, $password, $celular, $rol_id, $redes_sociales);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario registrado exitosamente.'); window.location.href='campus.html';</script>";
    } else {
        echo "<script>alert('Error al registrar usuario.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: registro_usuario.html");
    exit();
}
?>
