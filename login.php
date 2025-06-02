<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login – Galli Semillero Juvenil</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <h2>Iniciar Sesión</h2>
  <form action="validar_login.php" method="POST">
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Contraseña:</label>
    <input type="password" name="password" required>
    <button type="submit">Ingresar</button>
  </form>
</body>
</html>
