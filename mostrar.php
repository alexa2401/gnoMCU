<?php
include('test_data.php');

$hostname = "localhost";
$username = "root";
$password = "Aguacate.12";
$database = "gnoberto";

$conexion = new mysqli($hostname, $username, $password, $database);

// Función para obtener los últimos datos
function obtenerUltimosDatos() {
    global $conexion;
    $consulta = "SELECT temperatura, humedad FROM gnomo ORDER BY id DESC LIMIT 1";
    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $temperatura = $fila['temperatura'];
        $humedad = $fila['humedad'];
    } else {
        $temperatura = "No disponible";
        $humedad = "No disponible";
    }

    return [
        'temperatura' => $temperatura,
        'humedad' => $humedad,
    ];
}

// Si la petición es AJAX, devuelve los datos en formato JSON
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(obtenerUltimosDatos());
    exit;
}

// Si no es una petición AJAX, muestra la página normal
$datos = obtenerUltimosDatos();
$temperatura = $datos['temperatura'];
$humedad = $datos['humedad'];

$conexion->close();
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gnoberto - Visualización de Datos en Tiempo Real</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #F5F5DC;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            font-weight: bold;
            color: #FFA500;
            margin-top: 20px;
        }

        #datos-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        #imagen-container {
            margin-top: 20px;
        }

        #temperatura, #humedad {
            font-size: 18px;
            margin: 10px 0;
            color: #333;
        }

        .imagen-parametro {
            width: 150px; /* Ajusta el tamaño según sea necesario */
            height: 200px;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function actualizarDatos() {
            $.ajax({
                url: 'mostrar.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#temperatura').html('<img class="imagen-parametro" src="Gnomo1.png" alt="Imagen Temperatura">' + 'Temperatura: ' + data.temperatura);
                    $('#humedad').html('<img class="imagen-parametro" src="Gnomo2.png" alt="Imagen Humedad">' + 'Humedad: ' + data.humedad);
                },
                error: function () {
                    console.error('Error al actualizar datos');
                }
            });
        }

        setInterval(actualizarDatos, 5000);

        $(document).ready(function () {
            actualizarDatos();
        });
    </script>
</head>
<body>
    <h1>Gnoberto</h1>

    <div id="datos-container">
        <div id="imagen-container">
            <!-- Inserta tu imagen aquí -->
            <!-- <img src="tu_ruta_imagen.jpg" alt="Tu imagen"> -->
        </div>
        <p id="temperatura">Temperatura: --</p>
        <p id="humedad">Humedad: --</p>
    </div>
</body>
</html>