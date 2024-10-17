<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

try {
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("
        SELECT t.*, u.name, 
            (SELECT COUNT(*) FROM tasks WHERE status = 1 AND id IN (SELECT task_id FROM task_user WHERE user_id = :user_id)) AS completed_count
        FROM tasks AS t
        JOIN task_user AS tu ON t.id = tu.task_id
        JOIN users AS u ON tu.user_id = u.id
        WHERE tu.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $completedCount = $tasks ? $tasks[0]['completed_count'] : 0; 
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nubeico</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
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
            <h4 class="mb-4">Lista de Tareas de <?php echo htmlspecialchars($_SESSION['username']); ?></h4>
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="alert alert-info" >
                        <strong>Tareas Completadas:</strong> <span id="completed-count"><?php echo $completedCount; ?></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                    if ($tasks) {
                        foreach ($tasks as $task) {
                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="card bg-success-subtle" id="task-card-<?php echo $task['id']; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($task['title']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($task['description']); ?></p>
                                        <p class="card-text"><strong>Prioridad:</strong> <?php echo htmlspecialchars($task['priority']); ?></p>
                                        <p class="card-text"><strong>Estado:</strong> <span class="estado"><?php echo htmlspecialchars(($task['status'] == 1) ? 'Completada' : 'Pendiente'); ?></span></p>
                                        <p class="card-text"><small class="text-muted">Creada el <?php echo $task['created_at']; ?></small></p>
                                        
                                        <div class="row">
                                            <div class="col-3 col-md-3">
                                                <a href="task_detail.php?task_id=<?php echo $task['id']; ?>" class="btn btn-primary"> Detalles</a>
                                            </div>
                                            <div class="col-3 col-md-3">
                                                <a href="edit_task.php?task_id=<?php echo $task['id']; ?>" class="btn btn-primary"> Editar</a>
                                            </div>
                                            <div class="col-4 col-md-4">
                                                <?php if ($task['status'] == 0) : ?>
                                                    <button 
                                                        class="btn btn-success" 
                                                        onclick="mark_completed(<?php echo $task['id']; ?>, document.getElementById('task-card-<?php echo $task['id']; ?>'))">Completada
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No hay tareas disponibles.</p>";
                    }
                ?>
            </div>
        </div>
    <!-- Bootstrap JS and dependencies -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script>
    async function mark_completed(task_id, cardElement) {
        if (confirm('¿Estás seguro de que deseas marcar esta tarea como completada?')) {
            try {
                // Usar FormData para enviar los datos como POST
                const formData = new FormData();
                formData.append('task_id', task_id);

                const response = await fetch('mark_completed.php', {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Error en la solicitud: ' + response.statusText);
                }

                const data = await response.json();

                if (data.success) {
                    cardElement.querySelector('.estado').textContent = 'Completada';
                    cardElement.querySelector('.btn-success').style.display = 'none';

                    const completedCountElement = document.getElementById('completed-count');
                    completedCountElement.textContent = parseInt(completedCountElement.textContent) + 1;
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al marcar la tarea como completada.');
            }
        }
    }
    </script>
    </body>
</html>
