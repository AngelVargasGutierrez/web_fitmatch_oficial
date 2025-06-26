<?php
require_once __DIR__ . '/../app/controllers/SwipeController.php';
$swipeController = new SwipeController();
$swipeController->showSwipe();

// Verificar si el usuario está logueado
if (!$swipeController->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$currentUser = $swipeController->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitMatch - Swipe</title>
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
            overflow-x: hidden;
        }

        .swipe-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            cursor: grab;
            transition: transform 0.3s ease;
            user-select: none;
        }

        .profile-card:active {
            cursor: grabbing;
        }

        .profile-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
            position: relative;
            overflow: hidden;
        }

        .profile-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .profile-info {
            padding: 30px;
        }

        .profile-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }

        .profile-age {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 15px;
        }

        .profile-bio {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .profile-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tag {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .swipe-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .swipe-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .swipe-btn:hover {
            transform: scale(1.1);
        }

        .swipe-btn:active {
            transform: scale(0.95);
        }

        .btn-dislike {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
        }

        .btn-like {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
        }

        .btn-superlike {
            background: linear-gradient(45deg, #2196F3, #1976D2);
            color: white;
        }

        .swipe-overlay {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            opacity: 0;
            transform: rotate(-30deg);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .swipe-overlay.like {
            background: linear-gradient(45deg, #4CAF50, #45a049);
        }

        .swipe-overlay.dislike {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
        }

        .swipe-overlay.show {
            opacity: 1;
            transform: rotate(0deg);
        }

        .no-profiles {
            text-align: center;
            color: white;
            padding: 50px 20px;
        }

        .no-profiles i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .no-profiles h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .no-profiles p {
            opacity: 0.8;
        }

        .match-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .match-modal.show {
            opacity: 1;
            visibility: visible;
        }

        .match-content {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            transform: scale(0.7);
            transition: transform 0.3s ease;
        }

        .match-modal.show .match-content {
            transform: scale(1);
        }

        .match-icon {
            font-size: 4rem;
            color: #ff6b6b;
            margin-bottom: 20px;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .match-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }

        .match-subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .match-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .match-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .loading {
            text-align: center;
            color: white;
            padding: 50px 20px;
        }

        .loading i {
            font-size: 2rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .navbar {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #ff6b6b !important;
            transform: translateY(-2px);
        }

        .swipe-indicator {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .dropdown-menu {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .dropdown-item {
            color: #333;
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        @media (max-width: 480px) {
            .swipe-container {
                padding: 10px;
            }
            
            .profile-image {
                height: 300px;
            }
            
            .profile-info {
                padding: 20px;
            }
            
            .profile-name {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-heart me-2"></i>FitMatch
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-menu" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($currentUser['first_name'], 0, 1)); ?>
                        </div>
                        <span><?php echo htmlspecialchars($currentUser['first_name']); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="my_profile.php">
                            <i class="fas fa-user me-2"></i>Mi Perfil
                        </a></li>
                        <li><a class="dropdown-item" href="index.php">
                            <i class="fas fa-home me-2"></i>Inicio
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Swipe Container -->
    <div class="swipe-container">
        <div id="profiles-container">
            <div class="loading">
                <i class="fas fa-spinner"></i>
                <p>Cargando perfiles...</p>
            </div>
        </div>
    </div>

    <!-- Match Modal -->
    <div class="match-modal" id="matchModal">
        <div class="match-content">
            <i class="fas fa-heart match-icon"></i>
            <h2 class="match-title">¡Es un Match!</h2>
            <p class="match-subtitle">Has hecho match con este perfil</p>
            <button class="match-btn" onclick="closeMatchModal()">
                <i class="fas fa-check me-2"></i>Continuar
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        class SwipeApp {
            constructor() {
                this.profiles = [];
                this.currentIndex = 0;
                this.isDragging = false;
                this.startX = 0;
                this.startY = 0;
                this.currentX = 0;
                this.currentY = 0;
                
                this.init();
            }
            
            async init() {
                await this.loadProfiles();
                this.setupEventListeners();
            }
            
            async loadProfiles() {
                try {
                    const response = await fetch(`api/swipe_api.php?action=recommendations`);
                    const data = await response.json();
                    
                    if (data.success) {
                        this.profiles = data.data;
                        this.renderProfiles();
                    } else {
                        this.showNoProfiles();
                    }
                } catch (error) {
                    console.error('Error loading profiles:', error);
                    this.showNoProfiles();
                }
            }
            
            renderProfiles() {
                const container = document.getElementById('profiles-container');
                
                if (this.profiles.length === 0) {
                    this.showNoProfiles();
                    return;
                }
                
                container.innerHTML = '';
                
                // Mostrar solo el perfil actual
                const currentProfile = this.profiles[this.currentIndex];
                const profileCard = this.createProfileCard(currentProfile);
                container.appendChild(profileCard);
                
                // Configurar eventos de drag
                this.setupDragEvents(profileCard);
            }
            
            createProfileCard(profile) {
                const card = document.createElement('div');
                card.className = 'profile-card';
                card.dataset.userId = profile.id;
                
                // Generar imagen de placeholder basada en el nombre
                const initials = profile.username ? profile.username.substring(0, 2).toUpperCase() : 'U';
                const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57', '#ff9ff3'];
                const color = colors[profile.id % colors.length];
                
                card.innerHTML = `
                    <div class="swipe-indicator">${this.currentIndex + 1} de ${this.profiles.length}</div>
                    <div class="profile-image" style="background: ${color}">
                        <span style="position: relative; z-index: 2;">${initials}</span>
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name">${profile.username || 'Usuario'}</h2>
                        <p class="profile-age">${profile.age || '25'} años</p>
                        <p class="profile-bio">${profile.bio || 'Me encanta conocer gente nueva y compartir experiencias interesantes.'}</p>
                        <div class="profile-tags">
                            <span class="tag">Deportes</span>
                            <span class="tag">Música</span>
                            <span class="tag">Viajes</span>
                        </div>
                    </div>
                    <div class="swipe-overlay like">LIKE</div>
                    <div class="swipe-overlay dislike">NOPE</div>
                `;
                
                return card;
            }
            
            setupDragEvents(card) {
                card.addEventListener('mousedown', (e) => this.startDrag(e));
                card.addEventListener('touchstart', (e) => this.startDrag(e));
                
                document.addEventListener('mousemove', (e) => this.drag(e));
                document.addEventListener('touchmove', (e) => this.drag(e));
                
                document.addEventListener('mouseup', () => this.endDrag());
                document.addEventListener('touchend', () => this.endDrag());
            }
            
            startDrag(e) {
                this.isDragging = true;
                const touch = e.touches ? e.touches[0] : e;
                this.startX = touch.clientX;
                this.startY = touch.clientY;
                this.currentX = this.startX;
                this.currentY = this.startY;
            }
            
            drag(e) {
                if (!this.isDragging) return;
                
                e.preventDefault();
                const touch = e.touches ? e.touches[0] : e;
                this.currentX = touch.clientX;
                this.currentY = touch.clientY;
                
                const card = document.querySelector('.profile-card');
                if (card) {
                    const deltaX = this.currentX - this.startX;
                    const deltaY = this.currentY - this.startY;
                    const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
                    const angle = Math.atan2(deltaY, deltaX) * 180 / Math.PI;
                    
                    card.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${angle * 0.1}deg)`;
                    
                    // Mostrar overlay según dirección
                    const likeOverlay = card.querySelector('.swipe-overlay.like');
                    const dislikeOverlay = card.querySelector('.swipe-overlay.dislike');
                    
                    if (deltaX > 50) {
                        likeOverlay.classList.add('show');
                        dislikeOverlay.classList.remove('show');
                    } else if (deltaX < -50) {
                        dislikeOverlay.classList.add('show');
                        likeOverlay.classList.remove('show');
                    } else {
                        likeOverlay.classList.remove('show');
                        dislikeOverlay.classList.remove('show');
                    }
                }
            }
            
            endDrag() {
                if (!this.isDragging) return;
                
                this.isDragging = false;
                const card = document.querySelector('.profile-card');
                
                if (card) {
                    const deltaX = this.currentX - this.startX;
                    const threshold = 100;
                    
                    if (Math.abs(deltaX) > threshold) {
                        const action = deltaX > 0 ? 'like' : 'dislike';
                        this.swipeProfile(action);
                    } else {
                        // Reset position
                        card.style.transform = '';
                        card.querySelectorAll('.swipe-overlay').forEach(overlay => {
                            overlay.classList.remove('show');
                        });
                    }
                }
            }
            
            async swipeProfile(action) {
                const card = document.querySelector('.profile-card');
                const userId = card.dataset.userId;
                
                // Animar salida
                const direction = action === 'like' ? 1 : -1;
                card.style.transform = `translate(${direction * 500}px, 0px) rotate(${direction * 30}deg)`;
                card.style.opacity = '0';
                
                setTimeout(async () => {
                    // Enviar swipe al servidor
                    try {
                        const response = await fetch(`api/swipe_api.php?action=swipe`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                target_user_id: userId,
                                action: action
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success && data.match) {
                            this.showMatchModal();
                        }
                        
                        // Pasar al siguiente perfil
                        this.currentIndex++;
                        this.renderProfiles();
                        
                    } catch (error) {
                        console.error('Error saving swipe:', error);
                        this.currentIndex++;
                        this.renderProfiles();
                    }
                }, 300);
            }
            
            showMatchModal() {
                const modal = document.getElementById('matchModal');
                modal.classList.add('show');
            }
            
            showNoProfiles() {
                const container = document.getElementById('profiles-container');
                container.innerHTML = `
                    <div class="no-profiles">
                        <i class="fas fa-heart-broken"></i>
                        <h3>No hay más perfiles</h3>
                        <p>Has visto todos los perfiles disponibles. ¡Vuelve más tarde!</p>
                        <button class="btn btn-light mt-3" onclick="location.reload()">
                            <i class="fas fa-refresh me-2"></i>Recargar
                        </button>
                    </div>
                `;
            }
            
            setupEventListeners() {
                // Botones de swipe
                document.addEventListener('click', (e) => {
                    if (e.target.classList.contains('swipe-btn')) {
                        const action = e.target.dataset.action;
                        this.swipeProfile(action);
                    }
                });
            }
        }
        
        // Inicializar la aplicación
        const app = new SwipeApp();
        
        function closeMatchModal() {
            const modal = document.getElementById('matchModal');
            modal.classList.remove('show');
        }
        
        // Cerrar modal con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMatchModal();
            }
        });
    </script>
</body>
</html> 