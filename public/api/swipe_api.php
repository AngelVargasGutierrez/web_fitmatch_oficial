<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../../app/controllers/SwipeController.php';
require_once __DIR__ . '/../../app/controllers/UserController.php';

$userController = new UserController();

// Verificar si el usuario está logueado
if (!$userController->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

$userId = $_SESSION['user_id'];
$swipeController = new SwipeController();

// Obtener parámetros de la URL
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'recommendations':
        $swipeController->getRecommendations($userId);
        break;
        
    case 'swipe':
        // Obtener datos del POST
        $data = json_decode(file_get_contents('php://input'), true);
        $targetUserId = $data['target_user_id'] ?? null;
        $swipeAction = $data['action'] ?? null; // 'like' o 'dislike'
        
        if (!$targetUserId || !$swipeAction) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Faltan parámetros requeridos']);
            exit;
        }
        
        $swipeController->saveSwipe($userId, $targetUserId, $swipeAction);
        break;
        
    case 'matches':
        $swipeController->getMatches($userId);
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Acción no encontrada']);
        break;
} 