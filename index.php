<?php
// Conexión a la base de datos
$host = 'localhost';
$user = 'root'; // Cambia si usas otro usuario
$password = ''; // Deja vacío si no tienes contraseña
$dbname = 'todolist';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Agregar tarea
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
    $task = $_POST['task'];
    $conn->query("INSERT INTO tasks (name) VALUES ('$task')");
}

// Marcar como completada
if (isset($_GET['done'])) {
    $id = $_GET['done'];
    $conn->query("UPDATE tasks SET completed = 1 WHERE id = $id");
}

// Eliminar tarea
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tasks WHERE id = $id");
}

// Obtener tareas
$result = $conn->query("SELECT * FROM tasks");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            border: none;
            background: #28a745;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        .task {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #fff;
            margin: 5px 0;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }
        .task span {
            flex-grow: 1;
            text-align: left;
        }
        .completed {
            text-decoration: line-through;
            color: #6c757d;
        }
        .actions a {
            text-decoration: none;
            margin-left: 10px;
            padding: 5px;
            border-radius: 5px;
            color: white;
        }
        .actions .done {
            background: #007bff;
        }
        .actions .delete {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Tareas</h1>
        <form method="POST">
            <input type="text" name="task" required>
            <button type="submit">Agregar</button>
        </form>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="task">
                    <span class="<?= $row['completed'] ? 'completed' : '' ?>">
                        <?= $row['name'] ?>
                    </span>
                    <div class="actions">
                        <a class="done" href="?done=<?= $row['id'] ?>">✔</a>
                        <a class="delete" href="?delete=<?= $row['id'] ?>">❌</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
