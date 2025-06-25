<?php
require_once __DIR__ . '/../app/controllers/UserController.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->updateProfile();
} else {
    $userController->showMyProfile();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitMatch - Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .profile-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
            color: white;
            position: relative;
        }

        .profile-name {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .profile-username {
            color: #667eea;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .profile-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
        }

        .profile-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-save {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
        }

        .profile-info {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #333;
        }

        .info-value {
            color: #667eea;
        }

        .edit-mode {
            display: none;
        }

        .edit-mode.active {
            display: block;
        }

        .view-mode {
            display: block;
        }

        .view-mode.hidden {
            display: none;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .profile-container {
                margin: 20px;
                padding: 30px 20px;
            }
            
            .profile-stats {
                flex-direction: column;
                gap: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h1 class="profile-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
            <p class="profile-username">@<?php echo htmlspecialchars($user['username']); ?></p>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-number">25</div>
                    <div class="stat-label">Matches</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">150</div>
                    <div class="stat-label">Likes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $user['age'] ?? '25'; ?></div>
                    <div class="stat-label">Años</div>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Modo Vista -->
        <div class="view-mode" id="viewMode">
            <div class="profile-section">
                <h3 class="section-title">
                    <i class="fas fa-user-circle"></i>
                    Información Personal
                </h3>
                <div class="profile-info">
                    <div class="info-item">
                        <span class="info-label">Nombre:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Género:</span>
                        <span class="info-value"><?php echo ucfirst($user['gender'] ?? 'No especificado'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de nacimiento:</span>
                        <span class="info-value"><?php echo $user['birth_date'] ? date('d/m/Y', strtotime($user['birth_date'])) : 'No especificada'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ubicación:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['location'] ?? 'No especificada'); ?></span>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <h3 class="section-title">
                    <i class="fas fa-comment"></i>
                    Biografía
                </h3>
                <div class="profile-info">
                    <p><?php echo htmlspecialchars($user['bio'] ?? 'No has agregado una biografía aún.'); ?></p>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn btn-save" onclick="toggleEditMode()">
                    <i class="fas fa-edit me-2"></i>
                    Editar Perfil
                </button>
                <a href="swipe.php" class="btn btn-secondary">
                    <i class="fas fa-heart me-2"></i>
                    Ir a Swipe
                </a>
                <a href="logout.php" class="btn btn-secondary">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- Modo Edición -->
        <div class="edit-mode" id="editMode">
            <form method="POST" action="my_profile.php">
                <div class="profile-section">
                    <h3 class="section-title">
                        <i class="fas fa-user-edit"></i>
                        Editar Información Personal
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="first_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="last_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Género</label>
                                <select name="gender" class="form-control" required>
                                    <option value="male" <?php echo ($user['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Masculino</option>
                                    <option value="female" <?php echo ($user['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Femenino</option>
                                    <option value="other" <?php echo ($user['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input type="date" name="birth_date" class="form-control" 
                                       value="<?php echo $user['birth_date'] ?? ''; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ubicación</label>
                        <input type="text" name="location" class="form-control" 
                               value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="profile-section">
                    <h3 class="section-title">
                        <i class="fas fa-comment-edit"></i>
                        Editar Biografía
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Biografía</label>
                        <textarea name="bio" class="form-control" rows="4" 
                                  placeholder="Cuéntanos sobre ti..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save me-2"></i>
                        Guardar Cambios
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEditMode() {
            const viewMode = document.getElementById('viewMode');
            const editMode = document.getElementById('editMode');
            
            if (viewMode.classList.contains('hidden')) {
                viewMode.classList.remove('hidden');
                editMode.classList.remove('active');
            } else {
                viewMode.classList.add('hidden');
                editMode.classList.add('active');
            }
        }
    </script>
</body>
</html> 