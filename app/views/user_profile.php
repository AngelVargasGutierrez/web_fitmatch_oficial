<?php if (isset($user)): ?>
    <h1>Perfil de <?php echo htmlspecialchars($user['username']); ?></h1>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Género: <?php echo htmlspecialchars($user['gender']); ?></p>
    <p>Biografía: <?php echo htmlspecialchars($user['bio']); ?></p>
<?php else: ?>
    <p>Usuario no encontrado.</p>
<?php endif; ?> 