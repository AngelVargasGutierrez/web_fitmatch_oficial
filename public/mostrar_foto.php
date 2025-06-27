<?php
require_once __DIR__ . '/../models/UserRepository.php';
$userRepo = new UserRepository();
$userId = $_GET['id'] ?? null;
if (!$userId) {
    http_response_code(404);
    exit;
}
$sql = "SELECT foto_perfil_blob FROM users WHERE id = ?";
$db = $userRepo->db;
$result = $db->fetch($sql, [$userId]);
if ($result && !empty($result['foto_perfil_blob'])) {
    header("Content-Type: image/jpeg"); // O detecta el tipo real si quieres
    echo $result['foto_perfil_blob'];
} else {
    // Imagen por defecto
    readfile(__DIR__ . '/default_profile.png');
}
exit; 