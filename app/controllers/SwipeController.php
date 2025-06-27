<?php
require_once __DIR__ . '/../../models/UserRepository.php';
require_once __DIR__ . '/../../models/MatchRepository.php';

class SwipeController {
    private $userRepository;
    private $matchRepository;
    
    public function __construct() {
        $this->userRepository = new UserRepository();
        $this->matchRepository = new MatchRepository();
    }
    
    // Obtener recomendaciones para el usuario
    public function getRecommendations($userId) {
        require_once __DIR__ . '/../../models/UserRepository.php';
        require_once __DIR__ . '/../../models/MatchRepository.php';
        $userRepo = new UserRepository();
        $matchRepo = new MatchRepository();
        $swipedIds = $matchRepo->getSwipedUserIdsMongo($userId);
        $swipedIds = array_map('strval', $swipedIds); // Forzar string
        $users = $userRepo->getAllUsers($userId);
        $filtered = array_filter($users, function($u) use ($swipedIds) {
            return !in_array((string)$u['id'], $swipedIds);
        });
        echo json_encode(['success' => true, 'data' => array_values($filtered)]);
    }
    
    // Guardar swipe (like o dislike)
    public function saveSwipe($userId, $targetUserId, $action) {
        require_once __DIR__ . '/../../models/MatchRepository.php';
        $matchRepo = new MatchRepository();
        $result = $matchRepo->saveSwipeMongo($userId, $targetUserId, $action);
        $isMatch = false;
        if ($action === 'like' && $matchRepo->checkMatchMongo($userId, $targetUserId)) {
            $matchRepo->saveMatchMongo($userId, $targetUserId);
            $isMatch = true;
        }
        echo json_encode(['success' => $result, 'match' => $isMatch]);
    }
    
    // Obtener matches del usuario
    public function getMatches($userId) {
        try {
            $matches = $this->matchRepository->getUserMatches($userId);
            
            // Obtener información completa de los usuarios en los matches
            $matchesWithUsers = [];
            foreach ($matches as $match) {
                $otherUserId = ($match->user1_id == $userId) ? $match->user2_id : $match->user1_id;
                $user = $this->userRepository->findById($otherUserId);
                
                if ($user) {
                    $matchesWithUsers[] = [
                        'match_id' => (string)$match->_id,
                        'user' => $user,
                        'timestamp' => $match->timestamp,
                        'created_at' => $match->created_at
                    ];
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $matchesWithUsers
            ]);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Error al obtener matches'
            ]);
        }
    }

    public function showSwipe() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $userController = new UserController();
        $currentUser = $userController->getCurrentUser();
        include __DIR__ . '/../views/swipe.php';
    }
} 