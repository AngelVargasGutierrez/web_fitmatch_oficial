<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../models/ChatRepository.php';

$chatRepo = new ChatRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $matchId = $input['match_id'] ?? null;
    $message = $input['message'] ?? '';
    $senderId = $_SESSION['user_id'] ?? null;

    if (!$matchId || !$senderId || !$message) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        exit;
    }

    $chatRepo->saveMessage($matchId, $senderId, $message);
    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'history') {
    $matchId = $_GET['match_id'] ?? null;
    $limit = $_GET['limit'] ?? 50;

    if (!$matchId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'match_id requerido']);
        exit;
    }

    $messages = $chatRepo->getMessages($matchId, $limit);
    echo json_encode(['success' => true, 'messages' => $messages]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'matches') {
    $userId = $_SESSION['user_id'];
    require_once __DIR__ . '/../../app/controllers/MessageController.php';
    (new MessageController())->getMatches($userId);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Método no permitido']); 