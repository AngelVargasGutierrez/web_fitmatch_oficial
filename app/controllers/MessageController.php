<?php
require_once __DIR__ . '/../../models/ChatRepository.php';

class MessageController {
    private $chatRepository;
    public function __construct() {
        $this->chatRepository = new ChatRepository();
    }
    public function sendMessage($roomId, $from, $to, $msg) {
        $message = [
            'from' => $from,
            'to' => $to,
            'msg' => $msg,
            'timestamp' => date('c')
        ];
        $this->chatRepository->saveMessage($roomId, $message);
        echo json_encode(['status' => 'ok']);
    }
    public function getMessages($roomId, $limit = 50) {
        $messages = $this->chatRepository->getMessages($roomId, $limit);
        $result = array_map('json_decode', $messages);
        echo json_encode($result);
    }
    public function getMatches($userId) {
        require_once __DIR__ . '/../../models/MatchRepository.php';
        require_once __DIR__ . '/../../models/UserRepository.php';
        $matchRepo = new MatchRepository();
        $userRepo = new UserRepository();
        $matchIds = $matchRepo->getMatchesMongo($userId);
        $matches = [];
        foreach ($matchIds as $id) {
            $user = $userRepo->findById($id);
            if (isset($user['foto_perfil_blob'])) {
                unset($user['foto_perfil_blob']);
            }
            $matches[] = $user;
        }
        echo json_encode(['success' => true, 'matches' => $matches]);
    }
} 