<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Universidades</title>
    <style>
        .container {
            width: 50%;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }

        .header, .row {
            display: flex;
            padding: 10px;
            justify-content: center;
        }

        .header {
            font-weight: bold;
            background-color: #4CAF50;
            color: white;
        }

        .row:nth-child(even) {
            background-color: #f2f2f2;
        }

        .row:nth-child(odd) {
            background-color: #e0e0e0;
        }

        /* Simple border for clarity */
        .container {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>Universidad</div>
    </div>

    <?php
    $mysqli = new mysqli("localhost", "iweb", "sjbdls", "sisacad");

    if ($mysqli->connect_errno) {
        printf("Fallo la conexión: %s\n", $mysqli->connect_error);
        exit();
    }

    // Consulta para obtener solo los nombres de las universidades
    $consulta = "SELECT name AS uname FROM universities";

    if ($resultado = $mysqli->query($consulta)) {
        while ($obj = $resultado->fetch_object()) {
            echo "<div class='row'>
                    <div>$obj->uname</div>
                  </div>";
        }
        $resultado->close();
    }

    $mysqli->close();
    ?>
</div>

</body>
</html>
