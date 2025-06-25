<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../../app/controllers/UserController.php';

$userController = new UserController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            switch ($action) {
                case 'check_email':
                    $email = $input['email'] ?? '';
                    if (empty($email)) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'Email requerido']);
                        exit;
                    }
                    
                    $exists = $userController->userRepository->emailExists($email);
                    echo json_encode(['success' => true, 'exists' => $exists]);
                    break;
                    
                case 'register':
                    $result = $userController->register($input);
                    if ($result['success']) {
                        http_response_code(201);
                    } else {
                        http_response_code(400);
                    }
                    echo json_encode($result);
                    break;
                    
                case 'login':
                    $email = $input['email'] ?? '';
                    $password = $input['password'] ?? '';
                    
                    if (empty($email) || empty($password)) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'Email y contraseña requeridos']);
                        exit;
                    }
                    
                    $result = $userController->login($email, $password);
                    if ($result['success']) {
                        http_response_code(200);
                    } else {
                        http_response_code(401);
                    }
                    echo json_encode($result);
                    break;
                    
                case 'logout':
                    $result = $userController->logout();
                    echo json_encode($result);
                    break;
                    
                case 'update_profile':
                    if (!$userController->isLoggedIn()) {
                        http_response_code(401);
                        echo json_encode(['success' => false, 'error' => 'No autorizado']);
                        exit;
                    }
                    
                    $userId = $_SESSION['user_id'];
                    $result = $userController->updateProfile($userId, $input);
                    
                    if ($result['success']) {
                        http_response_code(200);
                    } else {
                        http_response_code(400);
                    }
                    echo json_encode($result);
                    break;
                    
                case 'change_password':
                    if (!$userController->isLoggedIn()) {
                        http_response_code(401);
                        echo json_encode(['success' => false, 'error' => 'No autorizado']);
                        exit;
                    }
                    
                    $userId = $_SESSION['user_id'];
                    $currentPassword = $input['current_password'] ?? '';
                    $newPassword = $input['new_password'] ?? '';
                    
                    if (empty($currentPassword) || empty($newPassword)) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'Contraseña actual y nueva contraseña requeridas']);
                        exit;
                    }
                    
                    $result = $userController->changePassword($userId, $currentPassword, $newPassword);
                    
                    if ($result['success']) {
                        http_response_code(200);
                    } else {
                        http_response_code(400);
                    }
                    echo json_encode($result);
                    break;
                    
                case 'delete_account':
                    if (!$userController->isLoggedIn()) {
                        http_response_code(401);
                        echo json_encode(['success' => false, 'error' => 'No autorizado']);
                        exit;
                    }
                    
                    $userId = $_SESSION['user_id'];
                    $password = $input['password'] ?? '';
                    
                    if (empty($password)) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'Contraseña requerida']);
                        exit;
                    }
                    
                    $result = $userController->deleteAccount($userId, $password);
                    echo json_encode($result);
                    break;
                    
                default:
                    http_response_code(404);
                    echo json_encode(['success' => false, 'error' => 'Acción no encontrada']);
                    break;
            }
            break;
            
        case 'GET':
            switch ($action) {
                case 'profile':
                    if (!$userController->isLoggedIn()) {
                        http_response_code(401);
                        echo json_encode(['success' => false, 'error' => 'No autorizado']);
                        exit;
                    }
                    
                    $user = $userController->getCurrentUser();
                    if ($user) {
                        // No enviar la contraseña
                        unset($user['password']);
                        echo json_encode(['success' => true, 'user' => $user]);
                    } else {
                        http_response_code(404);
                        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
                    }
                    break;
                    
                case 'is_logged_in':
                    $isLoggedIn = $userController->isLoggedIn();
                    $user = null;
                    
                    if ($isLoggedIn) {
                        $user = $userController->getCurrentUser();
                        if ($user) {
                            unset($user['password']);
                        }
                    }
                    
                    echo json_encode([
                        'success' => true, 
                        'is_logged_in' => $isLoggedIn,
                        'user' => $user
                    ]);
                    break;
                    
                case 'check_availability':
                    $type = $_GET['type'] ?? '';
                    $value = $_GET['value'] ?? '';
                    if ($type === 'username') {
                        $exists = $userController->userRepository->usernameExists($value);
                    } elseif ($type === 'email') {
                        $exists = $userController->userRepository->emailExists($value);
                    } else {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'Tipo no válido']);
                        exit;
                    }
                    echo json_encode(['available' => !$exists]);
                    break;
                    
                default:
                    http_response_code(404);
                    echo json_encode(['success' => false, 'error' => 'Acción no encontrada']);
                    break;
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error del servidor: ' . $e->getMessage()]);
} 