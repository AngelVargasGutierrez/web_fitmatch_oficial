<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Limpiar datos del formulario de sesión
unset($_SESSION['form_data'], $_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitMatch - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: float 20s ease-in-out infinite;
            z-index: -1;
        }
        @keyframes float { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-20px) rotate(180deg); } }
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            animation: slideInUp 0.6s ease-out;
        }
        @keyframes slideInUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
        .register-header { text-align: center; margin-bottom: 30px; }
        .register-logo { font-size: 2.5rem; color: #667eea; margin-bottom: 10px; }
        .register-title { font-size: 1.8rem; font-weight: 700; color: #333; margin-bottom: 5px; }
        .register-subtitle { color: #666; font-size: 1rem; }
        .form-group { margin-bottom: 20px; position: relative; }
        .form-control { background: rgba(255, 255, 255, 0.9); border: 2px solid #e1e5e9; border-radius: 10px; padding: 15px 20px; font-size: 1rem; transition: all 0.3s ease; width: 100%; }
        .form-control:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); transform: translateY(-2px); }
        .form-control.error { border-color: #ff6b6b; }
        .form-control.success { border-color: #4CAF50; }
        .form-control::placeholder { color: #999; }
        .input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #667eea; z-index: 3; }
        .form-control-with-icon { padding-left: 50px; }
        .validation-message { font-size: 0.85rem; margin-top: 5px; display: flex; align-items: center; gap: 5px; }
        .validation-message.error { color: #ff6b6b; }
        .validation-message.success { color: #4CAF50; }
        .btn-register { background: linear-gradient(45deg, #667eea, #764ba2); border: none; color: white; padding: 15px; border-radius: 10px; font-size: 1.1rem; font-weight: 600; width: 100%; cursor: pointer; transition: all 0.3s ease; margin-bottom: 20px; }
        .btn-register:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3); }
        .btn-register:active { transform: translateY(-1px); }
        .btn-register:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .divider { text-align: center; margin: 20px 0; position: relative; }
        .divider::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #e1e5e9; }
        .divider span { background: rgba(255, 255, 255, 0.95); padding: 0 15px; color: #666; font-size: 0.9rem; }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: #667eea; text-decoration: none; font-weight: 600; transition: color 0.3s ease; }
        .login-link a:hover { color: #764ba2; }
        .alert { border-radius: 10px; border: none; padding: 15px; margin-bottom: 20px; font-weight: 500; }
        .alert-success { background: linear-gradient(45deg, #4CAF50, #45a049); color: white; }
        .alert-danger { background: linear-gradient(45deg, #ff6b6b, #ee5a24); color: white; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .progress-bar { width: 100%; height: 6px; background: #e1e5e9; border-radius: 3px; margin-bottom: 20px; overflow: hidden; }
        .progress-fill { height: 100%; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 3px; transition: width 0.3s ease; width: 0%; }
        .floating-hearts { position: fixed; width: 100%; height: 100%; pointer-events: none; z-index: 1; }
        .floating-heart { position: absolute; color: rgba(255, 255, 255, 0.3); font-size: 1.5rem; animation: floatHeart 6s ease-in-out infinite; }
        .floating-heart:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .floating-heart:nth-child(2) { top: 20%; right: 15%; animation-delay: 2s; }
        .floating-heart:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }
        .floating-heart:nth-child(4) { bottom: 20%; right: 10%; animation-delay: 1s; }
        .floating-heart:nth-child(5) { top: 50%; left: 5%; animation-delay: 3s; }
        @keyframes floatHeart { 0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); opacity: 0.3; } 50% { transform: translateY(-30px) rotate(180deg) scale(1.2); opacity: 0.6; } }
        @media (max-width: 768px) { .register-container { margin: 20px; padding: 30px 20px; } .form-row { grid-template-columns: 1fr; } .register-title { font-size: 1.5rem; } }
    </style>
</head>
<body>
    <div class="floating-hearts">
        <i class="fas fa-heart floating-heart"></i>
        <i class="fas fa-heart floating-heart"></i>
        <i class="fas fa-heart floating-heart"></i>
        <i class="fas fa-heart floating-heart"></i>
        <i class="fas fa-heart floating-heart"></i>
    </div>
    <div class="register-container">
        <div class="register-header">
            <div class="register-logo">
                <i class="fas fa-heart"></i>
            </div>
            <h1 class="register-title">Únete a FitMatch</h1>
            <p class="register-subtitle">Crea tu cuenta y encuentra tu pareja ideal</p>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="" id="registerForm">
            <div class="form-row">
                <div class="form-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="first_name" class="form-control form-control-with-icon" placeholder="Nombre" required value="<?php echo $_SESSION['form_data']['first_name'] ?? ''; ?>">
                    <?php if (isset($_SESSION['errors']['first_name'])): ?>
                        <div class="validation-message error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $_SESSION['errors']['first_name']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="last_name" class="form-control form-control-with-icon" placeholder="Apellido" required value="<?php echo $_SESSION['form_data']['last_name'] ?? ''; ?>">
                    <?php if (isset($_SESSION['errors']['last_name'])): ?>
                        <div class="validation-message error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $_SESSION['errors']['last_name']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <i class="fas fa-at input-icon"></i>
                <input type="text" name="username" class="form-control form-control-with-icon" placeholder="Nombre de usuario" required value="<?php echo $_SESSION['form_data']['username'] ?? ''; ?>">
                <div class="validation-message" id="usernameValidation"></div>
                <?php if (isset($_SESSION['errors']['username'])): ?>
                    <div class="validation-message error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $_SESSION['errors']['username']; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" name="email" class="form-control form-control-with-icon" placeholder="Email" required value="<?php echo $_SESSION['form_data']['email'] ?? ''; ?>">
                <div class="validation-message" id="emailValidation"></div>
                <?php if (isset($_SESSION['errors']['email'])): ?>
                    <div class="validation-message error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $_SESSION['errors']['email']; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <i class="fas fa-venus-mars input-icon"></i>
                    <select name="gender" class="form-control form-control-with-icon" required>
                        <option value="">Selecciona tu género</option>
                        <option value="male" <?php echo ($_SESSION['form_data']['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="female" <?php echo ($_SESSION['form_data']['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Femenino</option>
                        <option value="other" <?php echo ($_SESSION['form_data']['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                    <?php if (isset($_SESSION['errors']['gender'])): ?>
                        <div class="validation-message error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $_SESSION['errors']['gender']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <i class="fas fa-calendar input-icon"></i>
                    <input type="date" name="birth_date" class="form-control form-control-with-icon" placeholder="Fecha de nacimiento" required value="<?php echo $_SESSION['form_data']['birth_date'] ?? ''; ?>">
                    <?php if (isset($_SESSION['errors']['birth_date'])): ?>
                        <div class="validation-message error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $_SESSION['errors']['birth_date']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <i class="fas fa-map-marker-alt input-icon"></i>
                <input type="text" name="location" class="form-control form-control-with-icon" placeholder="Ciudad" required value="<?php echo $_SESSION['form_data']['location'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <i class="fas fa-comment input-icon"></i>
                <textarea name="bio" class="form-control form-control-with-icon" placeholder="Cuéntanos sobre ti..." rows="3" style="resize: none;"><?php echo $_SESSION['form_data']['bio'] ?? ''; ?></textarea>
            </div>
            <div class="form-group">
                <i class="fas fa-dumbbell input-icon"></i>
                <select name="sport" class="form-control form-control-with-icon" required>
                    <option value="">¿Qué deporte te gusta?</option>
                    <option value="gimnasio" <?php echo (isset($_SESSION['form_data']['sport']) && $_SESSION['form_data']['sport'] === 'gimnasio') ? 'selected' : ''; ?>>Gimnasio</option>
                    <option value="calistenia" <?php echo (isset($_SESSION['form_data']['sport']) && $_SESSION['form_data']['sport'] === 'calistenia') ? 'selected' : ''; ?>>Calistenia</option>
                    <option value="bicicleta" <?php echo (isset($_SESSION['form_data']['sport']) && $_SESSION['form_data']['sport'] === 'bicicleta') ? 'selected' : ''; ?>>Bicicleta</option>
                    <option value="futbol" <?php echo (isset($_SESSION['form_data']['sport']) && $_SESSION['form_data']['sport'] === 'futbol') ? 'selected' : ''; ?>>Fútbol</option>
                    <option value="crossfit" <?php echo (isset($_SESSION['form_data']['sport']) && $_SESSION['form_data']['sport'] === 'crossfit') ? 'selected' : ''; ?>>Crossfit</option>
                </select>
                <?php if (isset($_SESSION['errors']['sport'])): ?>
                    <div class="validation-message error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $_SESSION['errors']['sport']; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <i class="fas fa-star input-icon"></i>
                <select name="interests" id="interests-select" class="form-control form-control-with-icon" required onchange="toggleOtherInterest()">
                    <option value="">¿Cuáles son tus intereses?</option>
                    <option value="conocer amigos" <?php echo (isset($_SESSION['form_data']['interests']) && $_SESSION['form_data']['interests'] === 'conocer amigos') ? 'selected' : ''; ?>>Conocer amigos</option>
                    <option value="conocer gente para entrenar" <?php echo (isset($_SESSION['form_data']['interests']) && $_SESSION['form_data']['interests'] === 'conocer gente para entrenar') ? 'selected' : ''; ?>>Conocer gente para entrenar</option>
                    <option value="otro" <?php echo (isset($_SESSION['form_data']['interests']) && !in_array($_SESSION['form_data']['interests'], ['conocer amigos','conocer gente para entrenar'])) ? 'selected' : ''; ?>>Otro</option>
                </select>
                <input type="text" name="other_interest" id="other-interest" class="form-control mt-2" style="display:none;" placeholder="Especifica tu interés" value="<?php echo (!empty($_SESSION['form_data']['interests']) && !in_array($_SESSION['form_data']['interests'], ['conocer amigos','conocer gente para entrenar'])) ? htmlspecialchars($_SESSION['form_data']['interests']) : ''; ?>">
                <?php if (isset($_SESSION['errors']['interests'])): ?>
                    <div class="validation-message error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $_SESSION['errors']['interests']; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control form-control-with-icon" placeholder="Contraseña" required>
                    <div class="validation-message" id="passwordValidation"></div>
                    <?php if (isset($_SESSION['errors']['password'])): ?>
                        <div class="validation-message error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $_SESSION['errors']['password']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="confirm_password" class="form-control form-control-with-icon" placeholder="Confirmar contraseña" required>
                    <div class="validation-message" id="confirmPasswordValidation"></div>
                    <?php if (isset($_SESSION['errors']['confirm_password'])): ?>
                        <div class="validation-message error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $_SESSION['errors']['confirm_password']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-register" id="submitBtn">
                <i class="fas fa-user-plus me-2"></i>
                Crear Cuenta
            </button>
        </form>
        <div class="divider">
            <span>¿Ya tienes cuenta?</span>
        </div>
        <div class="login-link">
            <a href="login.php">
                <i class="fas fa-sign-in-alt me-2"></i>
                Iniciar Sesión
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación en tiempo real
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        const progressFill = document.getElementById('progressFill');
        let formProgress = 0;
        const requiredFields = form.querySelectorAll('[required]');
        // Verificar disponibilidad de username y email
        let usernameTimeout, emailTimeout;
        document.querySelector('input[name="username"]').addEventListener('input', function() {
            clearTimeout(usernameTimeout);
            usernameTimeout = setTimeout(() => checkAvailability('username', this.value), 500);
        });
        document.querySelector('input[name="email"]').addEventListener('input', function() {
            clearTimeout(emailTimeout);
            emailTimeout = setTimeout(() => checkAvailability('email', this.value), 500);
        });
        async function checkAvailability(type, value) {
            if (value.length < 3) return;
            try {
                const response = await fetch('api/user_api.php?action=check_availability&type=' + type + '&value=' + encodeURIComponent(value));
                const data = await response.json();
                const validationDiv = document.getElementById(`${type}Validation`);
                const input = document.querySelector(`input[name="${type}"]`);
                if (data.available) {
                    validationDiv.innerHTML = '<i class="fas fa-check-circle"></i> Disponible';
                    validationDiv.className = 'validation-message success';
                    input.classList.remove('error');
                    input.classList.add('success');
                } else {
                    validationDiv.innerHTML = '<i class="fas fa-times-circle"></i> Ya está en uso';
                    validationDiv.className = 'validation-message error';
                    input.classList.remove('success');
                    input.classList.add('error');
                }
            } catch (error) {
                console.error('Error checking availability:', error);
            }
        }
        // Validación de contraseña
        document.querySelector('input[name="password"]').addEventListener('input', function() {
            const password = this.value;
            const validationDiv = document.getElementById('passwordValidation');
            let isValid = true;
            let message = '';
            if (password.length < 6) {
                isValid = false;
                message = 'Mínimo 6 caracteres';
            } else if (!/(?=.*[a-z])/.test(password)) {
                isValid = false;
                message = 'Al menos una minúscula';
            } else if (!/(?=.*[A-Z])/.test(password)) {
                isValid = false;
                message = 'Al menos una mayúscula';
            } else if (!/(?=.*\d)/.test(password)) {
                isValid = false;
                message = 'Al menos un número';
            } else {
                message = 'Contraseña válida';
            }
            validationDiv.innerHTML = `<i class="fas fa-${isValid ? 'check' : 'times'}-circle"></i> ${message}`;
            validationDiv.className = `validation-message ${isValid ? 'success' : 'error'}`;
            this.classList.toggle('error', !isValid);
            this.classList.toggle('success', isValid);
        });
        // Validación de confirmación de contraseña
        document.querySelector('input[name="confirm_password"]').addEventListener('input', function() {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = this.value;
            const validationDiv = document.getElementById('confirmPasswordValidation');
            if (confirmPassword === password && confirmPassword.length > 0) {
                validationDiv.innerHTML = '<i class="fas fa-check-circle"></i> Las contraseñas coinciden';
                validationDiv.className = 'validation-message success';
                this.classList.remove('error');
                this.classList.add('success');
            } else if (confirmPassword.length > 0) {
                validationDiv.innerHTML = '<i class="fas fa-times-circle"></i> Las contraseñas no coinciden';
                validationDiv.className = 'validation-message error';
                this.classList.remove('success');
                this.classList.add('error');
            } else {
                validationDiv.innerHTML = '';
                this.classList.remove('success', 'error');
            }
        });
        // Actualizar progreso del formulario
        function updateProgress() {
            let filledFields = 0;
            requiredFields.forEach(field => {
                if (field.value.trim() !== '') {
                    filledFields++;
                }
            });
            formProgress = (filledFields / requiredFields.length) * 100;
            progressFill.style.width = formProgress + '%';
            // Habilitar/deshabilitar botón de envío
            const isValid = formProgress === 100 && 
                           !document.querySelector('.form-control.error') &&
                           document.querySelector('input[name="password"]').value === 
                           document.querySelector('input[name="confirm_password"]').value;
            submitBtn.disabled = !isValid;
        }
        // Escuchar cambios en todos los campos requeridos
        requiredFields.forEach(field => {
            field.addEventListener('input', updateProgress);
            field.addEventListener('change', updateProgress);
        });
        // Inicializar progreso
        updateProgress();
        function toggleOtherInterest() {
            var select = document.getElementById('interests-select');
            var otherInput = document.getElementById('other-interest');
            if (select.value === 'otro') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            toggleOtherInterest();
        });
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Construir el objeto con los datos del formulario
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            fetch('api/user_api.php?action=register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    window.location.href = response.redirect || 'swipe.php';
                } else if (response.error) {
                    alert(response.error);
                } else if (response.errors) {
                    // Si hay errores de validación, los mostramos todos
                    alert(Object.values(response.errors).join('\n'));
                } else {
                    alert('Error al registrar usuario');
                }
            })
            .catch(err => {
                alert('Error de red o del servidor');
                console.error(err);
            });
        });
    </script>
</body>
</html> 