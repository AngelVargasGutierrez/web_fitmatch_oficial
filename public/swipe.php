<?php
require_once __DIR__ . '/../app/controllers/UserController.php';
$userController = new UserController();
if (!$userController->isLoggedIn()) {
    header('Location: login.php');
    exit;
}
$currentUser = $userController->getCurrentUser();
include __DIR__ . '/../app/views/swipe.php';
// No debe haber nada más después de este include 