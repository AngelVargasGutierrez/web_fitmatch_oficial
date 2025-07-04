<?php
require_once __DIR__ . '/../../models/UserRepository.php';
require_once __DIR__ . '/../utils/EmailHelper.php';

class UserController {
    public $userRepository;
    
    public function __construct() {
        $this->userRepository = new UserRepository();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Mostrar perfil de usuario
    public function showProfile($id) {
        $user = $this->userRepository->findById($id);
        include __DIR__ . '/../views/user_profile.php';
    }
    
    // Mostrar página de login
    public function showLogin() {
        if ($this->isLoggedIn()) {
            header('Location: /swipe.php');
            exit;
        }
        include __DIR__ . '/../views/login.php';
    }
    
    // Mostrar página de registro
    public function showRegister() {
        if ($this->isLoggedIn()) {
            header('Location: /swipe.php');
            exit;
        }
        include __DIR__ . '/../views/register.php';
    }
    
    // Procesar login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor completa todos los campos';
            unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email']);
            $this->showLogin();
            return;
        }
        
        $user = $this->userRepository->authenticateUser($email, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['success'] = '¡Bienvenido de vuelta, ' . $user['first_name'] . '!';
            header('Location: /swipe.php');
            exit;
        } else {
            $_SESSION['error'] = 'Email o contraseña incorrectos';
            unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email']);
            $this->showLogin();
            return;
        }
    }
    
    // Procesar registro
    public function register($userData = null) {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }

        if ($userData === null) {
            $userData = $_POST;
        }

        $userData = [
            'username' => $userData['username'] ?? '',
            'email' => $userData['email'] ?? '',
            'password' => $userData['password'] ?? '',
            'confirm_password' => $userData['confirm_password'] ?? '',
            'first_name' => $userData['first_name'] ?? '',
            'last_name' => $userData['last_name'] ?? '',
            'gender' => $userData['gender'] ?? '',
            'birth_date' => $userData['birth_date'] ?? '',
            'bio' => $userData['bio'] ?? '',
            'location' => $userData['location'] ?? '',
            'sport' => $userData['sport'] ?? '',
            'interests' => (isset($userData['interests']) && $userData['interests'] === 'otro') ? ($userData['other_interest'] ?? '') : ($userData['interests'] ?? '')
        ];

        // Validaciones
        $errors = $this->validateRegistration($userData);
        
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }
        
        // Encriptar la contraseña SOLO después de validar
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        try {
            $userId = $this->userRepository->createUser($userData);
            if ($userId) {
                // Login automático
                $user = $this->userRepository->findById($userId);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $token = bin2hex(random_bytes(32));
                $this->userRepository->setVerificationToken($userId, $token);
                $verificationLink = "http://localhost/verify_email.php?token=$token";
                sendVerificationEmail($userData['email'], $userData['first_name'], $verificationLink);
                echo json_encode(['success' => true, 'redirect' => '/swipe.php']);
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo crear el usuario.']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
    
    // Logout
    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    // Verificar si el usuario está logueado
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // Obtener usuario actual
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return $this->userRepository->getUserWithProfile($_SESSION['user_id']);
    }
    
    // Mostrar perfil del usuario actual
    public function showMyProfile() {
        if (!$this->isLoggedIn()) {
            file_put_contents(__DIR__ . '/../../logs/user_errors.log', date('Y-m-d H:i:s') . " - Usuario no logueado\n", FILE_APPEND);
            header('Location: /login.php');
            exit;
        }
        $user = $this->getCurrentUser();
        $userId = $_SESSION['user_id'];
        // Ya no se usa datos_usuario, la foto está en users
        // Log para depuración
        if (!$user) {
            $logMsg = date('Y-m-d H:i:s') . " - Error: Usuario no encontrado en getCurrentUser. user_id: $userId\n";
            $logMsg .= 'SESSION: ' . print_r($_SESSION, true) . "\n";
            file_put_contents(__DIR__ . '/../../logs/user_errors.log', $logMsg, FILE_APPEND);
        }
        include __DIR__ . '/../views/my_profile.php';
    }
    
    // Actualizar perfil
    public function updateProfile() {
        if (!$this->isLoggedIn()) {
            header('Location: /login.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: my_profile.php');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        $userData = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'birth_date' => $_POST['birth_date'] ?? ''
        ];
        
        $profileData = [
            'bio' => $_POST['bio'] ?? '',
            'location' => $_POST['location'] ?? '',
            'interests' => $_POST['interests'] ?? []
        ];
        
        $userUpdated = $this->userRepository->updateUser($userId, $userData);
        $profileUpdated = $this->userRepository->updateProfile($userId, $profileData);
        
        if ($userUpdated || $profileUpdated) {
            $_SESSION['success'] = 'Perfil actualizado correctamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar el perfil';
        }
        
        header('Location: my_profile.php');
        exit;
    }
    
    // Validar datos de registro
    private function validateRegistration($userData) {
        $errors = [];
        
        // Validar campos requeridos
        if (empty($userData['username'])) {
            $errors['username'] = 'El nombre de usuario es requerido';
        } elseif (strlen($userData['username']) < 3) {
            $errors['username'] = 'El nombre de usuario debe tener al menos 3 caracteres';
        } elseif ($this->userRepository->usernameExists($userData['username'])) {
            $errors['username'] = 'Este nombre de usuario ya está en uso';
        }
        
        if (empty($userData['email'])) {
            $errors['email'] = 'El email es requerido';
        } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido';
        } elseif ($this->userRepository->emailExists($userData['email'])) {
            $errors['email'] = 'Este email ya está registrado';
        }
        
        if (empty($userData['password'])) {
            $errors['password'] = 'La contraseña es requerida';
        } elseif (strlen($userData['password']) < 6) {
            $errors['password'] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        if ($userData['password'] !== $userData['confirm_password']) {
            $errors['confirm_password'] = 'Las contraseñas no coinciden';
        }
        
        if (empty($userData['first_name'])) {
            $errors['first_name'] = 'El nombre es requerido';
        }
        
        if (empty($userData['last_name'])) {
            $errors['last_name'] = 'El apellido es requerido';
        }
        
        if (empty($userData['gender'])) {
            $errors['gender'] = 'El género es requerido';
        }
        
        if (empty($userData['birth_date'])) {
            $errors['birth_date'] = 'La fecha de nacimiento es requerida';
        } else {
            $birthDate = new DateTime($userData['birth_date']);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
            
            if ($age < 18) {
                $errors['birth_date'] = 'Debes ser mayor de 18 años';
            }
        }
        
        return $errors;
    }
    
    // API para verificar disponibilidad de username/email
    public function checkAvailability() {
        header('Content-Type: application/json');
        
        $type = $_GET['type'] ?? '';
        $value = $_GET['value'] ?? '';
        
        if (empty($type) || empty($value)) {
            echo json_encode(['error' => 'Parámetros requeridos']);
            return;
        }
        
        $exists = false;
        
        switch ($type) {
            case 'username':
                $exists = $this->userRepository->usernameExists($value);
                break;
            case 'email':
                $exists = $this->userRepository->emailExists($value);
                break;
            default:
                echo json_encode(['error' => 'Tipo no válido']);
                return;
        }
        
        echo json_encode(['available' => !$exists]);
    }
    
    // Mostrar formulario de edición de perfil extendido
    public function showEditProfile() {
        if (!$this->isLoggedIn()) {
            header('Location: /login.php');
            exit;
        }
        
        // Procesar formulario si es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            
            // Validaciones básicas
            if (empty($username) || empty($email)) {
                $_SESSION['error'] = 'Por favor completa todos los campos';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'El email no es válido';
            } else {
                // Actualizar usuario usando el método específico
                $updated = $this->userRepository->updateBasicInfo($userId, $username, $email);
                
                if ($updated) {
                    $_SESSION['success'] = 'Perfil actualizado correctamente';
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    header('Location: /my_profile.php');
                    exit;
                } else {
                    $_SESSION['error'] = 'Error al actualizar el perfil';
                }
            }
        }
        
        // Mostrar formulario
        $userId = $_SESSION['user_id'];
        $user = $this->userRepository->findById($userId);
        include __DIR__ . '/../views/edit_profile.php';
    }

    // Procesar actualización de perfil extendido
    public function updateProfileExtended() {
        if (!$this->isLoggedIn()) {
            header('Location: /login.php');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $fotoPerfil = null;
        // Manejar subida de foto
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
            $fileName = 'perfil_' . $userId . '_' . time() . '.' . $ext;
            $destino = __DIR__ . '/../../public/uploads/' . $fileName;
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
                $fotoPerfil = '/uploads/' . $fileName;
            }
        }
        // Actualizar username/email y foto en users
        $user = $this->userRepository->findById($userId);
        $user['username'] = $username;
        $user['email'] = $email;
        if ($fotoPerfil !== null) {
            $user['foto_perfil'] = $fotoPerfil;
        }
        $this->userRepository->updateUser($userId, $user);
        $_SESSION['success'] = 'Perfil actualizado correctamente';
        header('Location: /swipe.php');
        exit;
    }
} 