<?php
// Suponiendo que existe un modelo ActivityRepository (puedes crearlo similar a los otros repositorios)
require_once __DIR__ . '/../../models/ActivityRepository.php';

class ActivityController {
    private $activityRepository;
    public function __construct() {
        $this->activityRepository = new ActivityRepository();
    }
    public function listActivities($userId) {
        $activities = $this->activityRepository->getUserActivities($userId);
        echo json_encode($activities);
    }
    public function getCommonInterests($userId1, $userId2) {
        $common = $this->activityRepository->getCommonInterests($userId1, $userId2);
        echo json_encode($common);
    }
} 