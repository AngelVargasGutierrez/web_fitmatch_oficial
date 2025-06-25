<?php
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/MessageController.php';
require_once __DIR__ . '/../app/controllers/ActivityController.php';
require_once __DIR__ . '/../app/controllers/RecommendationController.php';

header('Content-Type: application/json');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// El endpoint de registro de usuario se maneja en /public/api/user_api.php?action=register
// Eliminado para evitar duplicidad y confusión.
// if (strpos($path, '/api/user/register') === 0 && $method === 'POST') {
//     $data = json_decode(file_get_contents('php://input'), true);
//     $userController = new UserController();
//     // Simular $_POST para compatibilidad con el controlador
//     $_POST = $data;
//     $userController->register();
// } elseif (strpos($path, '/api/user/profile') === 0 && $method === 'GET') {
//     $id = isset($_GET['id']) ? intval($_GET['id']) : 1;
//     (new UserController())->showProfile($id);
// } elseif (strpos($path, '/api/messages/send') === 0 && $method === 'POST') {
//     $data = json_decode(file_get_contents('php://input'), true);
//     $roomId = $data['roomId'] ?? null;
//     $from = $data['from'] ?? null;
//     $to = $data['to'] ?? null;
//     $msg = $data['msg'] ?? null;
//     (new MessageController())->sendMessage($roomId, $from, $to, $msg);
// } elseif (strpos($path, '/api/messages/history') === 0 && $method === 'GET') {
//     $roomId = $_GET['roomId'] ?? null;
//     $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
//     (new MessageController())->getMessages($roomId, $limit);
// } elseif (strpos($path, '/api/activities/list') === 0 && $method === 'GET') {
//     $userId = isset($_GET['userId']) ? intval($_GET['userId']) : 1;
//     (new ActivityController())->listActivities($userId);
// } elseif (strpos($path, '/api/activities/common') === 0 && $method === 'GET') {
//     $userId1 = isset($_GET['userId1']) ? intval($_GET['userId1']) : 1;
//     $userId2 = isset($_GET['userId2']) ? intval($_GET['userId2']) : 2;
//     (new ActivityController())->getCommonInterests($userId1, $userId2);
// } elseif (strpos($path, '/api/recommendations') === 0 && $method === 'GET') {
//     $userId = isset($_GET['userId']) ? intval($_GET['userId']) : 1;
//     (new RecommendationController())->getRecommendations($userId);
// } elseif (strpos($path, '/api/preferences') === 0 && $method === 'POST') {
//     $data = json_decode(file_get_contents('php://input'), true);
//     $userId = $data['userId'] ?? null;
//     $preferences = $data['preferences'] ?? [];
//     (new RecommendationController())->savePreferences($userId, $preferences);
// } else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint no encontrado']);
// } 