<?php
// Conexión a la base de datos
$mysqli = new mysqli("localhost", "iweb", "sjbdls", "sisacad");

if ($mysqli->connect_errno) {
    printf("Fallo la conexión: %s\n", $mysqli->connect_error);
    exit();
}

// Variables para la operación CRUD
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Crear una nueva profesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && $action == 'create') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? 1 : 0;
    $created_id = 1; // ID de quien crea el registro

    $stmt = $mysqli->prepare("INSERT INTO professions (name, description, status, created, created_id) VALUES (?, ?, ?, NOW(), ?)");
    $stmt->bind_param("ssii", $name, $description, $status, $created_id);
    $stmt->execute();
    $stmt->close();
    header("Location: ?action=read");
    exit();
}

// Actualizar una profesión existente
if ($_SERVER["REQUEST_METHOD"] == "POST" && $action == 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? 1 : 0;
    $modified_id = 1; // ID de quien modifica el registro

    $stmt = $mysqli->prepare("UPDATE professions SET name = ?, description = ?, status = ?, modified = NOW(), modified_id = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $name, $description, $status, $modified_id, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ?action=read");
    exit();
}

// Eliminar una profesión
if ($action == 'delete' && $id > 0) {
    $stmt = $mysqli->prepare("DELETE FROM professions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ?action=read");
    exit();
}

// Obtener una profesión para editar
$profesion = null;
if ($action == 'edit' && $id > 0) {
    $stmt = $mysqli->prepare("SELECT * FROM professions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $profesion = $resultado->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Profesiones</title>
    <link rel="stylesheet" href="styles.css"> <!-- Puedes agregar tu archivo CSS aquí -->
</head>
<body>

<?php if ($action == 'create' || ($action == 'edit' && $profesion)): ?>
    <!-- Formulario para Crear o Editar -->
    <h1><?php echo $action == 'create' ? 'Añadir Nueva Profesión' : 'Editar Profesión'; ?></h1>
    <form method="POST" action="?action=<?php echo $action == 'create' ? 'create' : 'update'; ?>">
        <?php if ($action == 'edit'): ?>
            <input type="hidden" name="id" value="<?php echo $profesion['id']; ?>">
        <?php endif; ?>
        <label for="name">Nombre:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $profesion ? $profesion['name'] : ''; ?>" required><br><br>
        <label for="description">Descripción:</label><br>
        <textarea id="description" name="description"><?php echo $profesion ? $profesion['description'] : ''; ?></textarea><br><br>
        <label for="status">Estado:</label><br>
        <input type="checkbox" id="status" name="status" value="1" <?php echo $profesion && $profesion['status'] ? 'checked' : ''; ?>> Activo<br><br>
        <input type="submit" value="Guardar">
    </form>
    <a href="?action=read">Volver al listado</a>

<?php else: ?>
    <!-- Listado de Profesiones -->
    <h1>Listado de Profesiones</h1>
    <a href="?action=create">Añadir Profesión</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php
        // Mostrar todas las profesiones
        $consulta = "SELECT * FROM professions";
        $resultado = $mysqli->query($consulta);
        while ($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo $fila['name']; ?></td>
            <td><?php echo $fila['description']; ?></td>
            <td><?php echo $fila['status'] ? 'Activo' : 'Inactivo'; ?></td>
            <td>
                <a href="?action=edit&id=<?php echo $fila['id']; ?>">Editar</a>
                <a href="?action=delete&id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta profesión?')">Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </table>
<?php endif; ?>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$mysqli->close();
?>

