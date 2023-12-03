<?php

$hostname = "localhost";
$username = "root";
$password = "Aguacate.12";
$database = "gnoberto";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn){
    die("mamo la puta conexion" . mysqli_connect_error());
}


if (isset($_POST["temperatura"])&& isset($_POST["humedad"])){

    $id = 2;
    $t = $_POST["temperatura"];
    $h = $_POST["humedad"];

    $sql = "INSERT INTO gnomo (id, temperatura, humedad) VALUE (".$id.", ".$t.", ".$h.")";

    if (mysqli_query($conn, $sql)){
        echo "conexion correcta";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

}



?>