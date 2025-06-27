<?php
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'register':
        include '../app/views/register.php';
        break;
    case 'login':
        include '../app/views/login.php';
        break;
    case 'swipe':
        include '../app/views/swipe.php';
        break;
    case 'mensajes':
        include '../app/views/mensajes.php';
        break;
    case 'mi_perfil':
        include '../app/views/my_profile.php';
        break;
    case 'edit_profile':
        include '../app/views/edit_profile.php';
        break;
    case 'sidebar':
        include '../app/views/sidebar.php';
        break;
    case 'user_profile':
        include '../app/views/user_profile.php';
        break;
    default:
        echo '<h1>Bienvenido a FitMatch</h1>';
        break;
} 