<?php
require_once __DIR__ . '/MySQLDB.php';

class UserRepository {
    private $db;
    public function __construct() {
        $this->db = MySQLDB::getInstance();
    }
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    public function findByPreferences($userId) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->fetchAll($sql, [$userId]);
    }
    public function updatePreferences($userId, $preferences) {
        $sql = "UPDATE users SET preferences = ? WHERE id = ?";
        return $this->db->execute($sql, [json_encode($preferences), $userId]);
    }
    // Métodos de autenticación
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->fetch($sql, [$email]);
    }
    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->fetch($sql, [$username]);
    }
    public function createUser($userData) {
        $sql = "INSERT INTO users (username, first_name, last_name, email, password, birth_date, gender, bio, interests, location, sport, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $params = [
            $userData['username'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['email'],
            $userData['password'],
            $userData['birth_date'],
            $userData['gender'],
            $userData['bio'] ?? '',
            $userData['interests'] ?? '',
            $userData['location'] ?? '',
            $userData['sport']
        ];

        try {
            $this->db->execute($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error al crear usuario: " . $e->getMessage());
        }
    }
    public function authenticateUser($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            // No devolver la contraseña en la respuesta
            unset($user['password']);
            return $user;
        }
        
        return false;
    }
    public function updateUser($id, $userData) {
        $sql = "UPDATE users SET 
                first_name = ?, 
                last_name = ?, 
                email = ?, 
                birth_date = ?, 
                gender = ?, 
                bio = ?, 
                interests = ?, 
                location = ?, 
                profile_picture = ?, 
                updated_at = NOW() 
                WHERE id = ?";
        
        $params = [
            $userData['first_name'],
            $userData['last_name'],
            $userData['email'],
            $userData['birth_date'],
            $userData['gender'],
            $userData['bio'] ?? '',
            $userData['interests'] ?? '',
            $userData['location'] ?? '',
            $userData['profile_picture'] ?? '',
            $id
        ];

        try {
            $this->db->execute($sql, $params);
            return true;
        } catch (Exception $e) {
            throw new Exception("Error al actualizar usuario: " . $e->getMessage());
        }
    }
    public function updateProfile($userId, $profileData) {
        $fields = [];
        $values = [];
        $allowedFields = ['bio', 'location', 'interests', 'preferences'];
        foreach ($allowedFields as $field) {
            if (isset($profileData[$field])) {
                $fields[] = "$field = ?";
                $values[] = is_array($profileData[$field]) ? json_encode($profileData[$field]) : $profileData[$field];
            }
        }
        if (empty($fields)) {
            return false;
        }
        $values[] = $userId;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->db->execute($sql, $values);
    }
    public function getUserWithProfile($userId) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $user = $this->db->fetch($sql, [$userId]);
        if ($user) {
            unset($user['password']);
        }
        return $user;
    }
    public function getAllUsers($excludeUserId = null) {
        $sql = "SELECT u.* FROM users u";
        $params = [];
        if ($excludeUserId) {
            $sql .= " WHERE u.id != ?";
            $params[] = $excludeUserId;
        }
        $users = $this->db->fetchAll($sql, $params);
        // Añadir foto_perfil desde datos_usuario si existe
        foreach ($users as &$user) {
            $datos = $this->getDatosUsuario($user['id']);
            if ($datos && !empty($datos['foto_perfil'])) {
                $user['foto_perfil'] = $datos['foto_perfil'];
            } else {
                $user['foto_perfil'] = null;
            }
        }
        return $users;
    }
    private function calculateAge($birthDate) {
        $birth = new DateTime($birthDate);
        $today = new DateTime();
        $age = $today->diff($birth);
        return $age->y;
    }
    public function emailExists($email, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $params = [$email];
        if ($excludeUserId) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }
        $result = $this->db->fetch($sql, $params);
        return isset($result['count']) && $result['count'] > 0;
    }
    public function usernameExists($username, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = ?";
        $params = [$username];
        if ($excludeUserId) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }
        $result = $this->db->fetch($sql, $params);
        return isset($result['count']) && $result['count'] > 0;
    }
    public function setVerificationToken($userId, $token) {
        $sql = "UPDATE users SET verification_token = ? WHERE id = ?";
        return $this->db->execute($sql, [$token, $userId]);
    }
    public function findByVerificationToken($token) {
        $sql = "SELECT * FROM users WHERE verification_token = ?";
        return $this->db->fetch($sql, [$token]);
    }
    public function verifyEmail($userId) {
        $sql = "UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = ?";
        return $this->db->execute($sql, [$userId]);
    }
    // Obtener datos de usuario extendidos
    public function getDatosUsuario($userId) {
        $sql = "SELECT * FROM datos_usuario WHERE user_id = ?";
        return $this->db->fetch($sql, [$userId]);
    }

    // Crear datos de usuario (si no existen)
    public function crearDatosUsuario($userId, $username, $email) {
        $sql = "INSERT INTO datos_usuario (user_id, username, email) VALUES (?, ?, ?)";
        return $this->db->execute($sql, [$userId, $username, $email]);
    }

    // Actualizar datos de usuario (nombre, email, foto)
    public function actualizarDatosUsuario($userId, $username = null, $email = null, $fotoPerfil = null) {
        $fields = [];
        $params = [];
        if ($username !== null) {
            $fields[] = "username = ?";
            $params[] = $username;
        }
        if ($email !== null) {
            $fields[] = "email = ?";
            $params[] = $email;
        }
        if ($fotoPerfil !== null) {
            $fields[] = "foto_perfil = ?";
            $params[] = $fotoPerfil;
        }
        if (empty($fields)) return false;
        $params[] = $userId;
        $sql = "UPDATE datos_usuario SET " . implode(', ', $fields) . " WHERE user_id = ?";
        return $this->db->execute($sql, $params);
    }
    // Agrega más métodos según lo necesites
} 