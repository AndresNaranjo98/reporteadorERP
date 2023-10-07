<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="images/icono.png" sizes="32x32">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
</body>
</html>

<?php
session_start();

require_once './db/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['usuario'];
    $password = $_POST['contrasena'];

    try {
        $stmt = $conexion->prepare("SELECT nombre, pass FROM Usuarios WHERE nombre = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetchAll();

        if ($user && $password === $user[0]['pass']) {
            $_SESSION['login'] = 1;
            header("Location: dashboard.php");
            exit();
        } else {
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Usuario/Contraseña incorrectos",
                text: "Inténtelo nuevamente"
                }).then(function () {
                    window.location.href = "index.php";
            });
            </script>';
        }
    } catch (PDOException $e) {
        die("Error al autenticar: " . $e->getMessage());
    }
}
?>
