<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }

        .header, .row {
            display: flex;
            padding: 10px;
            justify-content: space-between;
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

        .row div {
            width: 25%;
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
        <div>University</div>
        <div>Faculty</div>
        <div>Department</div>
        <div>Career</div>
    </div>

    <?php
    $mysqli = new mysqli("localhost", "iweb", "sjbdls", "sisacad");

    if ($mysqli->connect_errno) {
        printf("Fallo la conexión: %s\n", $mysqli->connect_error);
        exit();
    }

    $consulta = "SELECT u.name AS uname, f.name AS fname, d.name AS dname, c.name AS carreras FROM universities u
    INNER JOIN faculties f ON u.id = f.university_id
    INNER JOIN departments d ON f.id = d.faculty_id
    INNER JOIN careers c ON d.id = c.department_id;";

    if ($resultado = $mysqli->query($consulta)) {
        while ($obj = $resultado->fetch_object()) {
            echo "<div class='row'>
                    <div>$obj->uname</div>
                    <div>$obj->fname</div>
                    <div>$obj->dname</div>
                    <div>$obj->carreras</div>
                  </div>";
        }
        $resultado->close();
    }

    $mysqli->close();
    ?>
</div>

</body>
</html>
