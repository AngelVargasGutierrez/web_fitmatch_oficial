<?php
require_once 'MongoDBConn.php';

class ActivityRepository {
    private $manager;
    public function __construct() {
        $this->manager = (new MongoDBConn('fitmatch_activities'))->getManager();
    }
    public function getUserActivities($userId) {
        $filter = ['user_id' => $userId];
        $query = new MongoDB\Driver\Query($filter);
        $cursor = $this->manager->executeQuery('fitmatch_activities.activities', $query);
        return $cursor->toArray();
    }
    public function getCommonInterests($userId1, $userId2) {
        $activities1 = $this->getUserActivities($userId1);
        $activities2 = $this->getUserActivities($userId2);
        $set1 = array_map(fn($a) => $a->activity_type, $activities1);
        $set2 = array_map(fn($a) => $a->activity_type, $activities2);
        $common = array_values(array_intersect($set1, $set2));
        return $common;
    }
} 