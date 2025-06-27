<?php
require_once __DIR__ . '/../app/controllers/UserController.php';
$userController = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Subida de foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $userId = $_SESSION['user_id'];
        $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $fileName = 'perfil_' . $userId . '_' . time() . '.' . $ext;
        $destino = __DIR__ . '/uploads/' . $fileName;
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
            require_once __DIR__ . '/../models/UserRepository.php';
            $userRepo = new UserRepository();
            // Actualizar solo la foto en users
            $user = $userRepo->findById($userId);
            $user['foto_perfil'] = '/uploads/' . $fileName;
            $userRepo->updateUser($userId, $user);
            echo '<div class="alert alert-success text-center mt-5">Foto de perfil actualizada correctamente. Redirigiendo...</div>';
            echo '<script>setTimeout(function(){ window.location.href = "/swipe.php"; }, 1500);</script>';
            exit;
        } else {
            echo '<div class="alert alert-danger text-center mt-5">Error al subir la foto de perfil.</div>';
        }
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