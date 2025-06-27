<?php if (!isset($user) || !is_array($user)) { $user = []; } ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-3 col-lg-2 p-0">
      <?php include __DIR__ . '/sidebar.php'; ?>
    </div>
    <div class="col-md-9 col-lg-10 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
      <div class="card" style="max-width: 500px; width: 100%;">
        <div class="card-body">
          <h2 class="mb-4 text-center">Editar Perfil</h2>
          
          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>
          
          <form method="POST" action="/edit_profile.php">
            <div class="mb-3">
              <label for="username" class="form-label">Nombre de usuario</label>
              <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
              <a href="/my_profile.php" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 