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
        try {
            // Obtener usuarios que ya fueron swippeados
            $swipedUsers = $this->matchRepository->getSwipedUsers($userId);
            
            // Obtener recomendaciones basadas en preferencias
            $recommendations = $this->userRepository->findByPreferences($userId);
            
            // Filtrar usuarios ya swippeados
            $filteredRecommendations = array_filter($recommendations, function($user) use ($swipedUsers, $userId) {
                return !in_array($user['id'], $swipedUsers) && $user['id'] != $userId;
            });
            
            // Limitar a 10 recomendaciones
            $filteredRecommendations = array_slice($filteredRecommendations, 0, 10);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => array_values($filteredRecommendations)
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Error al obtener recomendaciones'
            ]);
        }
    }
    
    // Guardar swipe (like o dislike)
    public function saveSwipe($userId, $targetUserId, $action) {
        try {
            // Validar acción
            if (!in_array($action, ['like', 'dislike'])) {
                throw new Exception('Acción inválida');
            }
            
            // Guardar el swipe
            $saved = $this->matchRepository->saveSwipe($userId, $targetUserId, $action);
            
            if (!$saved) {
                throw new Exception('Error al guardar el swipe');
            }
            
            $response = ['success' => true, 'message' => 'Swipe guardado correctamente'];
            
            // Si es un like, verificar si hay match
            if ($action === 'like') {
                $isMatch = $this->matchRepository->checkMatch($userId, $targetUserId);
                
                if ($isMatch) {
                    // Crear el match
                    $this->matchRepository->createMatch($userId, $targetUserId);
                    $response['match'] = true;
                    $response['message'] = '¡Es un match!';
                } else {
                    $response['match'] = false;
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
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
} 