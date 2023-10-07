<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}

require_once './db/connection.php';

try {
    $stmt = $conexion->prepare("SELECT nombre FROM Empresa");
    $stmt->execute();
    $elementos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($elementos);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="./images/icono.png" sizes="32x32">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="row g-0 mt-5 mb-5 height-100">
            <div class="col-md-12">
                <div class="p-3 d-flex justify-content-end flex-column align-items-end">
                    <a class="bi bi-person-x-fill outStyle" href="logout.php"></a>
                </div>
                <div class="bg-white p-4 h-100">
                    <div class="p-3 d-flex justify-content-center flex-column align-items-center">
                        <span class="main-heading">Generar reporte de ventas</span>
                        <form action="./reports/ventas.php" method="POST" class="row g-3 needs-validation" novalidate>
                            <div class="form-data">
                                <label>Fecha inicial</label>
                                <input type="date" class="form-control w-100" name="fechaInicial" id="fechaInicial" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Debes llenar todos los campos</div>
                            </div>
                            <div class="form-data">
                                <label>Fecha final</label>
                                <input type="date" class="form-control w-100" name="fechaFinal" id="fechaFinal" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Debes llenar todos los campos</div>
                            </div>
                            <div class="form-data">
                                <label>Empresa</label>
                                <select name="empresa" id="empresa" class="form-control w-100" required>
                                    <?php
                                    foreach ($elementos as $elemento) {
                                        echo '<option>' . $elemento['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Debes llenar todos los campos</div>
                            </div>
                            <div class="signin-btn w-100 mt-3">
                                <button type="submit" class="btn btn-primary btn-block" onclick="mostrarAlertaEspera()">Generar Reporte</button>
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

    <script>
        function mostrarAlertaEspera() {
            setTimeout(function() {
                location.reload();
            }, 5000);
        }
    </script>

    <!-- <script>
        function mostrarAlertaEspera() {
            Swal.fire({
                title: 'Generando reporte...',
                text: 'Por favor, espere...',
                allowOutsideClick: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
            });
        }
    </script> -->

</body>

</html>