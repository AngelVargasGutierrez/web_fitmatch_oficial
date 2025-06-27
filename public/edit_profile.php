<?php
require_once __DIR__ . '/../app/controllers/UserController.php';
$userController = new UserController();
if (!$userController->isLoggedIn()) {
    header('Location: login.php');
    exit;
}
$userController->showEditProfile(); 