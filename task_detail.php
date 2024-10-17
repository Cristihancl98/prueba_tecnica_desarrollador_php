<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

try {
    if (isset($_GET['task_id'])) {
        $task_id = $_GET['task_id'];

        $stmt = $pdo->prepare("
            SELECT t.*, u.name
            FROM tasks AS t
            JOIN task_user AS tu ON t.id = tu.task_id
            JOIN users AS u ON tu.user_id = u.id
            WHERE t.id = :task_id AND tu.user_id = :user_id
        ");
        $stmt->execute([
            'task_id' => $task_id,
            'user_id' => $_SESSION['user_id']
        ]);

        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            echo "Tarea no encontrada o no tienes acceso a esta tarea.";
            exit;
        }
    } else {
        echo "ID de tarea no proporcionado.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detalles de la Tarea</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>

    <nav class="navbar navbar-expand-lg navbar-light bg-primary-subtle">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Nubeico</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <h2>Detalles de la Tarea</h2>
                <div class="card bg-success-subtle">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($task['title']); ?></h5>
                        <p class="card-text"><strong>Descripción:</strong> <?php echo htmlspecialchars($task['description']); ?></p>
                        <p class="card-text"><strong>Prioridad:</strong> <?php echo htmlspecialchars($task['priority']); ?></p>
                        <p class="card-text"><strong>Estado:</strong> <?php echo htmlspecialchars(($task['status'] == 1) ? 'Completada':'Pendiente'); ?></p>
                        <p class="card-text"><strong>Creada por:</strong> <?php echo htmlspecialchars($task['name']); ?></p>
                        <p class="card-text"><strong>Fecha de creación:</strong> <?php echo $task['created_at']; ?></p>
                        <p class="card-text"><strong>Última actualización:</strong> <?php echo $task['updated_at']; ?></p>
                        <a href="dashboard.php" class="btn btn-primary">Regresar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>