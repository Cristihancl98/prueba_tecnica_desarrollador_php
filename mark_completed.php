<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("UPDATE tasks SET status = 1 WHERE id = :task_id");
        $stmt->execute(['task_id' => $task_id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>