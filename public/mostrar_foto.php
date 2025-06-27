<?php
require_once __DIR__ . '/../models/UserRepository.php';
$userRepo = new UserRepository();
$userId = $_GET['id'] ?? null;
if (!$userId) {
    http_response_code(404);
    exit;
}
// Usar un método público para obtener la foto binaria
$user = $userRepo->findById($userId);
if ($user && !empty($user['foto_perfil_blob'])) {
    header("Content-Type: image/jpeg"); // O detecta el tipo real si quieres
    echo $user['foto_perfil_blob'];
} else {
    // Imagen por defecto
    readfile(__DIR__ . '/default_profile.png');
}
exit; 