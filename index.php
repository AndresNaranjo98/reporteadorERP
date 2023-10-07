<?php

session_start();

if (isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="icon" type="image/png" href="images/icono.png" sizes="32x32">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="row g-0 mt-5 mb-5 height-100">
            <div class="col-md-6">
                <div class="p-4 h-100 sidebar">
                    <img src="./images/logo.png" class="img-fluid" alt="logo">
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white p-4 h-100">
                    <div class="p-3 mt-20 d-flex justify-content-center flex-column align-items-center">
                        <span class="main-heading">Reporteador ERP</span>
                        <form action="login.php" method="POST" class="row g-3 needs-validation" novalidate>
                            <div class="form-data">
                                <label>Usuario</label>
                                <input type="text" class="form-control w-100" name="usuario" id="usuario" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Debes llenar todos los campos</div>
                            </div>
                            <div class="form-data">
                                <label>Contraseña</label>
                                <input type="password" name="contrasena" id="contrasena" class="form-control w-100" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Debes llenar todos los campos</div>
                            </div>
                            <div class="signin-btn w-100 mt-3">
                                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>

</body>

</html>