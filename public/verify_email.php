<?php
require_once __DIR__ . '/../models/UserRepository.php';
$token = $_GET['token'] ?? '';
if (!$token) {
    echo 'Token inválido.';
    exit;
}
$userRepo = new UserRepository();
$user = $userRepo->findByVerificationToken($token);
if ($user) {
    $userRepo->verifyEmail($user['id']);
    echo '¡Correo verificado exitosamente! Ya puedes iniciar sesión.';
} else {
    echo 'Token de verificación inválido o expirado.';
} 