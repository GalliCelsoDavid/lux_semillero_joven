<?php
require_once 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombres']);
    $apellido = trim($_POST['apellido']);
    $cuit = trim($_POST['cuit']);
    $direccion = trim($_POST['direccion']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $celular = trim($_POST['celular']);
    $rol_id = intval($_POST['rol_id']);
    $foto_nombre = '';

    // Procesar la foto si se subió
    if (!empty($_FILES['foto']['name'])) {
    // Obtener extensión del archivo original
    $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

    // Normalizar nombre de archivo: apellido_nombre (en minúsculas y sin espacios)
    $foto_nombre = strtolower(str_replace(' ', '_', $apellido . '_' . $nombre)) . '.' . $extension;

    // Ruta final de destino
    $ruta_destino = 'uploads/' . $foto_nombre;

    // Mover archivo subido
    move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
}


    // Verificar si ya existe ese usuario con ese mismo email y rol
    $stmt = $conn->prepare("
        SELECT u.id 
        FROM usuarios u
        JOIN usuario_roles ur ON u.id = ur.usuario_id
        WHERE u.email = ? AND ur.rol_id = ?
    ");
    $stmt->bind_param("si", $email, $rol_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('El correo ya está registrado con ese rol.'); window.history.back();</script>";
        exit();
    }
    $stmt->close();

    // Insertar usuario en tabla usuarios
    $stmt = $conn->prepare("
        INSERT INTO usuarios (cuit, apellido, nombre, direccion, email, password, celular, foto_perfil) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssssss", $cuit, $apellido, $nombre, $direccion, $email, $password, $celular, $foto_nombre);
    if ($stmt->execute()) {
        $usuario_id = $stmt->insert_id;
        $stmt->close();

        // Insertar rol en tabla usuario_roles
        $stmt = $conn->prepare("INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $usuario_id, $rol_id);
        if ($stmt->execute()) {
            echo "<script>alert('Usuario registrado exitosamente.'); window.location.href='campus.html';</script>";
        } else {
            echo "<script>alert('Error al asignar rol.'); window.history.back();</script>";
        }
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
