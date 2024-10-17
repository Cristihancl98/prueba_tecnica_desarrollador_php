<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
require 'config.php';

if (!isset($_GET['task_id'])) {
    header('Location: dashboard.php');
    exit;
}

$task_id = $_GET['task_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :task_id");
    $stmt->execute(['task_id' => $task_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        echo 'Tarea no encontrada';
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $priority = $_POST['priority'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE tasks SET title = :title, description = :description, priority = :priority, status = :status WHERE id = :task_id");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
            'status' => $status,
            'task_id' => $task_id
        ]);

        $message = '<div class="alert alert-success">Tarea actualizada exitosamente.</div>';
    }
} catch (PDOException $e) {
    $message = '<div class="alert alert-danger">Error al actualizar la tarea: ' . $e->getMessage() . '</div>';
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Tarea</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    </head>
    <body>

    <nav class="navbar navbar-expand-lg navbar-light bg-primary-subtle">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Nubeico</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">
                            <i class="bi bi-file-earmark-text"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="add_task.php" class="nav-link">
                            <i class="bi bi-plus-circle"></i> Agregar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card p-4 shadow-sm w-100">
                    <h4 class="mb-4">Editar Tarea</h4>
                    <?php echo $message; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="title" class="form-label">Título</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label">Prioridad</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="baja" <?php echo ($task['priority'] == 'baja') ? 'selected' : ''; ?>>Baja</option>
                                <option value="media" <?php echo ($task['priority'] == 'media') ? 'selected' : ''; ?>>Media</option>
                                <option value="alta" <?php echo ($task['priority'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="0" <?php echo ($task['status'] == 0) ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="1" <?php echo ($task['status'] == 1) ? 'selected' : ''; ?>>Completada</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success float-end">Actualizar Tarea</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
