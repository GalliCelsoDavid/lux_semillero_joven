<?php
session_start();
session_destroy();
echo "<script>alert('Cierre de sesión exitoso'); window.location.href='index.html';</script>";
