<?php if (isset($user)): ?>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-body text-center">
                <h2 class="mb-3">Mi Perfil</h2>
                <?php if (!empty($user['foto_perfil'])): ?>
                    <img src="<?php echo htmlspecialchars($user['foto_perfil']); ?>" alt="Foto de perfil" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                <?php else: ?>
                    <i class="fa fa-user-circle fa-5x mb-3 text-secondary"></i>
                <?php endif; ?>
                <h4><?php echo htmlspecialchars($user['username']); ?></h4>
                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p class="mb-1"><strong>Nombre:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
                <p class="mb-1"><strong>Apellido:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
                <a href="/Fitmatch/app/views/edit_profile.php" class="btn btn-primary mt-3">Editar Perfil</a>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Usuario no encontrado.</p>
<?php endif; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 