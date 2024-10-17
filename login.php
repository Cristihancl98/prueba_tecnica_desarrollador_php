<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); 
    exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Prueba Nubeico</title>
  </head>
  <body class="bg-light">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
                <h3 class="text-center mb-4">Iniciar sesión</h3>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Usuario</label>
                        <input type="email" class="form-control" id="username" name="username" placeholder="Ingrese su usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && ($password == $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['useremail'] = $user['email'];
        $_SESSION['username'] = $user['name'];
        header('Location: dashboard.php'); 
        exit;
    } else {
        header('Location: login.php?error=Usuario o contraseña incorrectos.');
        exit;
    }
}
?>