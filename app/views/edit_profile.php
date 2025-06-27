<?php if (!isset($datos) || !is_array($datos)) { $datos = []; } ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-3 col-lg-2 p-0">
      <?php include __DIR__ . '/sidebar.php'; ?>
    </div>
    <div class="col-md-9 col-lg-10 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
      <div class="card" style="max-width: 500px; width: 100%;">
        <div class="card-body">
          <h2 class="mb-4 text-center">Editar Perfil</h2>
          <form method="POST" action="/Fitmatch/public/actualizar_perfil.php" enctype="multipart/form-data">
            <div class="mb-3 text-center">
              <?php if (!empty($datos['foto_perfil'])): ?>
                <img src="<?php echo htmlspecialchars($datos['foto_perfil']); ?>" alt="Foto de perfil" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
              <?php else: ?>
                <i class="fa fa-user-circle fa-5x mb-2 text-secondary"></i>
              <?php endif; ?>
            </div>
            <div class="mb-3">
              <label for="foto_perfil" class="form-label">Foto de perfil</label>
              <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*">
            </div>
            <div class="mb-3">
              <label for="username" class="form-label">Nombre de usuario</label>
              <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($datos['username'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($datos['email'] ?? ''); ?>" required>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
              <a href="/Fitmatch/public/mi_perfil.php" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 