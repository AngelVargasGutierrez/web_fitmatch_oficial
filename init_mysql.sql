-- FitMatch Database Schema for MySQL/HeidiSQL
-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS fitmatch CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fitmatch;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    birth_date DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    bio TEXT,
    interests TEXT,
    location VARCHAR(100),
    profile_picture VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_gender (gender),
    INDEX idx_location (location),
    INDEX idx_created_at (created_at)
);

-- Tabla de perfiles (información adicional)
CREATE TABLE IF NOT EXISTS profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    height DECIMAL(5,2),
    weight DECIMAL(5,2),
    body_type ENUM('slim', 'athletic', 'average', 'curvy', 'plus_size'),
    education VARCHAR(100),
    occupation VARCHAR(100),
    smoking ENUM('never', 'occasionally', 'regularly', 'trying_to_quit'),
    drinking ENUM('never', 'occasionally', 'socially', 'regularly'),
    religion VARCHAR(50),
    political_views VARCHAR(50),
    languages TEXT,
    hobbies TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
);

-- Tabla de preferencias de usuario
CREATE TABLE IF NOT EXISTS user_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    min_age INT DEFAULT 18,
    max_age INT DEFAULT 100,
    preferred_gender ENUM('male', 'female', 'both') DEFAULT 'both',
    max_distance INT DEFAULT 50,
    interests_preference TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
);

-- Tabla de matches
CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    match_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_match (user1_id, user2_id),
    INDEX idx_user1 (user1_id),
    INDEX idx_user2 (user2_id),
    INDEX idx_match_date (match_date)
);

-- Tabla de likes/dislikes
CREATE TABLE IF NOT EXISTS swipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    swiper_id INT NOT NULL,
    swiped_id INT NOT NULL,
    action ENUM('like', 'dislike', 'super_like') NOT NULL,
    swipe_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (swiper_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (swiped_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_swipe (swiper_id, swiped_id),
    INDEX idx_swiper (swiper_id),
    INDEX idx_swiped (swiped_id),
    INDEX idx_action (action),
    INDEX idx_swipe_date (swipe_date)
);

-- Tabla de reportes
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    reported_id INT NOT NULL,
    reason ENUM('inappropriate', 'fake_profile', 'harassment', 'spam', 'other') NOT NULL,
    description TEXT,
    status ENUM('pending', 'reviewed', 'resolved', 'dismissed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_reporter (reporter_id),
    INDEX idx_reported (reported_id),
    INDEX idx_status (status)
);

-- Tabla de sesiones
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_session_token (session_token),
    INDEX idx_expires_at (expires_at)
);

-- Tabla de actividad del usuario
CREATE TABLE IF NOT EXISTS user_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type ENUM('login', 'logout', 'profile_view', 'swipe', 'message', 'match') NOT NULL,
    activity_data JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_activity_type (activity_type),
    INDEX idx_created_at (created_at)
);

-- Insertar datos de ejemplo
INSERT INTO users (first_name, last_name, email, password, birth_date, gender, bio, interests, location) VALUES
('María', 'García', 'maria@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1995-03-15', 'female', 'Me encanta viajar y conocer nuevas culturas', 'viajes, música, cocina, fotografía', 'Madrid'),
('Carlos', 'López', 'carlos@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1992-07-22', 'male', 'Deportista y amante de la naturaleza', 'deportes, senderismo, música, tecnología', 'Barcelona'),
('Ana', 'Martínez', 'ana@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1990-11-08', 'female', 'Artista y creativa, siempre buscando inspiración', 'arte, cine, literatura, yoga', 'Valencia'),
('David', 'Rodríguez', 'david@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1988-05-12', 'male', 'Ingeniero apasionado por la innovación', 'tecnología, ciencia, música, viajes', 'Sevilla'),
('Laura', 'Fernández', 'laura@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1993-09-30', 'female', 'Médica con corazón de aventurera', 'medicina, viajes, deportes, lectura', 'Bilbao');

-- Insertar preferencias de ejemplo
INSERT INTO user_preferences (user_id, min_age, max_age, preferred_gender, max_distance) VALUES
(1, 25, 35, 'male', 30),
(2, 23, 32, 'female', 25),
(3, 28, 40, 'male', 35),
(4, 25, 35, 'female', 30),
(5, 26, 38, 'male', 40);

-- Crear índices adicionales para optimización
CREATE INDEX idx_users_age ON users (birth_date);
CREATE INDEX idx_users_gender_location ON users (gender, location);
CREATE INDEX idx_swipes_recent ON swipes (swiper_id, swipe_date DESC);
CREATE INDEX idx_matches_active ON matches (user1_id, user2_id, is_active); 