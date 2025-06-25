<?php
require_once __DIR__ . '/RedisConn.php';

class ChatRepository {
    private $redis;

    public function __construct() {
        $this->redis = RedisConn::getInstance();
    }

    // Guardar mensaje en Redis
    public function saveMessage($matchId, $senderId, $message) {
        $msg = [
            'sender_id' => $senderId,
            'message' => $message,
            'timestamp' => time()
        ];
        $this->redis->rpush("chat:match_$matchId", json_encode($msg));
    }

    // Obtener últimos N mensajes
    public function getMessages($matchId, $limit = 50) {
        $messages = $this->redis->lrange("chat:match_$matchId", -$limit, -1);
        return array_map('json_decode', $messages);
    }
} 