<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        }
        .profile-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
        }
        .profile-info p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        .btn-edit {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php if (empty($user)): ?>
        <div class="alert alert-danger text-center mt-5">
            Error: No se pudo cargar el perfil del usuario.<br>
            <a href="/login.php" class="btn btn-primary mt-3">Iniciar sesión</a>
        </div>
        <?php exit; ?>
    <?php endif; ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3 col-lg-2 p-0">
          <?php include __DIR__ . '/sidebar.php'; ?>
        </div>
        <div class="col-md-9 col-lg-10">
          <div class="profile-container">
            <div class="profile-title">Mi Perfil</div>
            <div class="text-center mb-4">
                <img src="/mostrar_foto.php?id=<?php echo $user['id']; ?>" alt="Foto de perfil" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
            </div>
            <form method="POST" action="/my_profile.php" enctype="multipart/form-data" class="mb-4">
                <div class="mb-3">
                    <label for="foto_perfil" class="form-label">Actualizar foto de perfil</label>
                    <input type="file" name="foto_perfil" id="foto_perfil" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Subir Foto</button>
            </form>
            <div class="profile-info">
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
                <p><strong>Apellido:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
                <!-- Agrega más campos según tu modelo -->
            </div>
            <a href="/edit_profile.php" class="btn btn-primary btn-edit">Editar Perfil</a>
    <div class="profile-container">
        <div class="profile-title">Mi Perfil</div>
        <div class="text-center mb-4">
            <img src="/mostrar_foto.php?id=<?php echo $user['id']; ?>" alt="Foto de perfil" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
        </div>
        <form method="POST" action="/my_profile.php" enctype="multipart/form-data" class="mb-4">
            <div class="mb-3">
                <label for="foto_perfil" class="form-label">Actualizar foto de perfil</label>
                <input type="file" name="foto_perfil" id="foto_perfil" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Subir Foto</button>
        </form>
        <div class="profile-info">
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
            <p><strong>Apellido:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
            <!-- Agrega más campos según tu modelo -->
        </div>
        <a href="/edit_profile.php" class="btn btn-primary btn-edit">Editar Perfil</a>
    </div>
</body>
</html>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 