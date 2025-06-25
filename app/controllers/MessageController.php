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
} 