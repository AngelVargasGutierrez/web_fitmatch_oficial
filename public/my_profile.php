<?php
require_once __DIR__ . '/../app/controllers/UserController.php';
$userController = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Subida de foto de perfil (binaria)
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $userId = $_SESSION['user_id'];
        $imgData = file_get_contents($_FILES['foto_perfil']['tmp_name']);
        require_once __DIR__ . '/../models/UserRepository.php';
        $userRepo = new UserRepository();
        $userRepo->guardarFotoPerfilBinaria($userId, $imgData);
        echo '<div class="alert alert-success text-center mt-5">Foto de perfil actualizada correctamente. Redirigiendo...</div>';
        echo '<script>setTimeout(function(){ window.location.href = "/swipe.php"; }, 1500);</script>';
        exit;
    } else {
        echo '<div class="alert alert-danger text-center mt-5">No se seleccionó ninguna foto o hubo un error en la subida.</div>';
    }
    // Mostrar el perfil de nuevo si hubo error
    $user = $userController->getCurrentUser();
    $userController->showMyProfile();
} else {
    // Validar sesión antes de mostrar perfil
    if (!isset($_SESSION)) session_start();
    if (empty($_SESSION['user_id'])) {
        echo '<div class="alert alert-danger text-center mt-5">Debes iniciar sesión para ver tu perfil.<br><a href="/login.php" class="btn btn-primary mt-3">Iniciar sesión</a></div>';
        exit;
    }
    $user = $userController->getCurrentUser();
    if (empty($user)) {
        echo '<div class="alert alert-danger text-center mt-5">No se pudo cargar tu perfil.<br><a href="/login.php" class="btn btn-primary mt-3">Iniciar sesión</a></div>';
        exit;
    }
    $userController->showMyProfile();
} 