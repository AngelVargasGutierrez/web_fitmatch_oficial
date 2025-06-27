<?php
require_once __DIR__ . '/../app/controllers/UserController.php';
$userController = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->updateProfile();
} else {
    $userController->showMyProfile();
} 