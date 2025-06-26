<?php
// $currentUser debe estar definido por el controlador
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
        body {
            background: linear-gradient(135deg, #fff6e5 0%, #f6fff6 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            background: #fff;
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.03);
            padding: 2rem 1rem;
        }
        .sidebar .nav-link {
            color: #333;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .sidebar .nav-link.active {
            background: #ff9100;
            color: #fff;
        }
        .profile-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            overflow: hidden;
            max-width: 400px;
            margin: 40px auto 0 auto;
            position: relative;
            min-height: 500px;
        }
        .profile-card .profile-img {
            width: 100%;
            height: 220px;
            background: #f2f2f2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #bbb;
        }
        .profile-card .profile-info {
            padding: 2rem 2rem 1rem 2rem;
        }
        .profile-card .profile-info h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }
        .profile-card .profile-info .location {
            color: #888;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .profile-card .profile-info .sport {
            color: #ff9100;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .profile-card .profile-info .exp {
            color: #43a047;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .profile-card .profile-info .tags {
            margin-bottom: 0.5rem;
        }
        .profile-card .profile-info .tag {
            display: inline-block;
            background: #ffe0b2;
            color: #ff9100;
            border-radius: 12px;
            padding: 0.2rem 0.8rem;
            font-size: 0.9rem;
            margin-right: 0.3rem;
            margin-bottom: 0.2rem;
        }
        .profile-card .profile-info .bio {
            color: #444;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .swipe-actions {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0 1rem 0;
        }
        .swipe-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #eee;
            background: #fff;
            font-size: 2rem;
            color: #ff9100;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .swipe-btn.like {
            color: #43a047;
            border-color: #43a047;
        }
        .swipe-btn.dislike {
            color: #e53935;
            border-color: #e53935;
        }
        .swipe-btn:active {
            transform: scale(0.95);
        }
        .profiles-remaining {
            text-align: center;
            color: #888;
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .profile-card {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky">
                <h3 class="mb-4">FitMatch</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-home me-2"></i>Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-comments me-2"></i>Mensajes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_profile.php"><i class="fas fa-user me-2"></i>Mi Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-cog me-2"></i>Configuración</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 d-flex flex-column align-items-center justify-content-center">
            <div id="profileCardContainer" class="w-100"></div>
            <div class="swipe-actions">
                <button class="swipe-btn dislike" id="btnDislike"><i class="fas fa-times"></i></button>
                <button class="swipe-btn like" id="btnLike"><i class="fas fa-heart"></i></button>
            </div>
            <div class="profiles-remaining" id="profilesRemaining"></div>
        </main>
    </div>
</div>
<script>
let profiles = [];
let currentIndex = 0;

async function loadProfiles() {
    try {
        const response = await fetch('/Fitmatch/public/api/swipe_api.php?action=recommendations');
        const data = await response.json();
        if (data.success && Array.isArray(data.data)) {
            profiles = data.data;
            currentIndex = 0;
            renderProfile();
        } else {
            showNoProfiles();
        }
    } catch (error) {
        showNoProfiles();
    }
}

function renderProfile() {
    const container = document.getElementById('profileCardContainer');
    const remaining = document.getElementById('profilesRemaining');
    container.innerHTML = '';
    if (profiles.length === 0 || currentIndex >= profiles.length) {
        showNoProfiles();
        return;
    }
    const p = profiles[currentIndex];
    const card = document.createElement('div');
    card.className = 'profile-card';
    card.innerHTML = `
        <div class="profile-img">
            <i class="fas fa-user"></i>
        </div>
        <div class="profile-info">
            <h2>${p.first_name ? p.first_name : p.username}, ${p.age ? p.age : ''}</h2>
            <div class="location"><i class="fas fa-map-marker-alt me-1"></i> ${p.location ? p.location : 'No especificada'}</div>
            <div class="sport"><i class="fas fa-dumbbell me-1"></i> ${p.sport ? p.sport : 'Sin deporte'}</div>
            <div class="exp"><i class="fas fa-clock me-1"></i> ${p.experience ? p.experience : 'Sin experiencia'}</div>
            <div class="tags">
                ${(p.tags ? p.tags.map(tag => `<span class='tag'>${tag}</span>`).join('') : '')}
            </div>
            <div class="bio">${p.bio ? p.bio : ''}</div>
        </div>
    `;
    container.appendChild(card);
    remaining.textContent = `${profiles.length - currentIndex} perfiles restantes`;
}

function showNoProfiles() {
    const container = document.getElementById('profileCardContainer');
    const remaining = document.getElementById('profilesRemaining');
    container.innerHTML = `<div class='text-center text-muted py-5'><i class='fas fa-heart-broken fa-3x mb-3'></i><h4>No hay más perfiles disponibles</h4></div>`;
    remaining.textContent = '';
}

document.getElementById('btnLike').addEventListener('click', () => handleSwipe('like'));
document.getElementById('btnDislike').addEventListener('click', () => handleSwipe('dislike'));

async function handleSwipe(action) {
    if (profiles.length === 0 || currentIndex >= profiles.length) return;
    const p = profiles[currentIndex];
    try {
        await fetch('/Fitmatch/public/api/swipe_api.php?action=swipe', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ target_user_id: p.id, action })
        });
    } catch (e) {}
    currentIndex++;
    renderProfile();
}

window.onload = loadProfiles;
</script>
</body>
</html> 