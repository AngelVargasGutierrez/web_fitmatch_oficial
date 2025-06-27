<?php
require_once __DIR__ . '/../app/controllers/UserController.php';
$userController = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->updateProfile();
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