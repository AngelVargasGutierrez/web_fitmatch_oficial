<?php
require_once 'MongoDBConn.php';

class MatchRepository {
    private $manager;
    public function __construct() {
        $this->manager = (new MongoDBConn('fitmatch_matches'))->getManager();
    }
    public function findByUser($userId) {
        $filter = ['user1_id' => $userId];
        $query = new MongoDB\Driver\Query($filter);
        $cursor = $this->manager->executeQuery('fitmatch_matches.matches', $query);
        return $cursor->toArray();
    }
    
    // Guardar like o dislike
    public function saveSwipe($userId, $targetUserId, $action) {
        $bulk = new MongoDB\Driver\BulkWrite();
        
        $document = [
            'user_id' => $userId,
            'target_user_id' => $targetUserId,
            'action' => $action, // 'like' o 'dislike'
            'timestamp' => new MongoDB\BSON\UTCDateTime(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $bulk->insert($document);
        
        try {
            $result = $this->manager->executeBulkWrite('fitmatch_matches.swipes', $bulk);
            return $result->getInsertedCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Verificar si hay match (ambos usuarios se dieron like)
    public function checkMatch($userId, $targetUserId) {
        // Verificar si el usuario actual dio like al target
        $filter1 = [
            'user_id' => $userId,
            'target_user_id' => $targetUserId,
            'action' => 'like'
        ];
        
        // Verificar si el target dio like al usuario actual
        $filter2 = [
            'user_id' => $targetUserId,
            'target_user_id' => $userId,
            'action' => 'like'
        ];
        
        $query1 = new MongoDB\Driver\Query($filter1);
        $query2 = new MongoDB\Driver\Query($filter2);
        
        $cursor1 = $this->manager->executeQuery('fitmatch_matches.swipes', $query1);
        $cursor2 = $this->manager->executeQuery('fitmatch_matches.swipes', $query2);
        
        $userLiked = count(iterator_to_array($cursor1)) > 0;
        $targetLiked = count(iterator_to_array($cursor2)) > 0;
        
        return $userLiked && $targetLiked;
    }
    
    // Crear match si ambos se dieron like
    public function createMatch($userId, $targetUserId) {
        $bulk = new MongoDB\Driver\BulkWrite();
        
        $document = [
            'user1_id' => $userId,
            'user2_id' => $targetUserId,
            'timestamp' => new MongoDB\BSON\UTCDateTime(),
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];
        
        $bulk->insert($document);
        
        try {
            $result = $this->manager->executeBulkWrite('fitmatch_matches.matches', $bulk);
            return $result->getInsertedCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Obtener todos los matches de un usuario
    public function getUserMatches($userId) {
        $filter = [
            '$or' => [
                ['user1_id' => $userId],
                ['user2_id' => $userId]
            ]
        ];
        
        $query = new MongoDB\Driver\Query($filter);
        $cursor = $this->manager->executeQuery('fitmatch_matches.matches', $query);
        return iterator_to_array($cursor);
    }
    
    // Obtener usuarios que ya fueron swippeados por un usuario
    public function getSwipedUsers($userId) {
        $filter = ['user_id' => $userId];
        $query = new MongoDB\Driver\Query($filter);
        $cursor = $this->manager->executeQuery('fitmatch_matches.swipes', $query);
        
        $swipedUsers = [];
        foreach ($cursor as $swipe) {
            $swipedUsers[] = $swipe->target_user_id;
        }
        
        return $swipedUsers;
    }
} 