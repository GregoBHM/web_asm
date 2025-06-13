<?php
require_once BASE_PATH . '/models/Usuario.php';

$usuarioModel = new Usuario();
$usuario = $usuarioModel->obtenerPorId($_SESSION['usuario_id']);

// Función para validar contraseña segura
function validarPasswordSegura($password) {
    if (strlen($password) < 8) {
        return "La contraseña debe tener al menos 8 caracteres";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "La contraseña debe contener al menos una letra mayúscula";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "La contraseña debe contener al menos una letra minúscula";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "La contraseña debe contener al menos un número";
    }
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        return "La contraseña debe contener al menos un carácter especial";
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    try {
        // Solo validar password si se proporciona
        if (!empty($password)) {
            $validacion = validarPasswordSegura($password);
            if ($validacion !== true) {
                throw new Exception($validacion);
            }
        }

        // Solo actualizar la contraseña
        $usuarioModel->actualizarPassword($_SESSION['usuario_id'], $password);
        
        $mensaje = "Contraseña actualizada correctamente";
        $tipo_mensaje = "success";
    } catch (Exception $e) {
        $mensaje = "Error al actualizar contraseña: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}

// Calcular tiempo de acceso (ejemplo: desde el último login)
$tiempo_acceso = "Sesión activa desde " . date('d/m/Y H:i:s', $_SESSION['login_time'] ?? time());

require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <?php if (isset($mensaje)): ?>
                <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($mensaje) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Información Personal (Solo Lectura) -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-user"></i> Información Personal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['NOMBRE'] ?? '') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Apellido:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['APELLIDO'] ?? '') ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">DNI:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['DNI'] ?? '') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Celular:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['CELULAR'] ?? '') ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico:</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($usuario['EMAIL'] ?? '') ?>" readonly>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Los datos personales no pueden ser modificados. Contacta al administrador si necesitas realizar cambios.
                    </div>
                </div>
            </div>

            <!-- Cambio de Contraseña -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lock"></i> Cambiar Contraseña
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/public/index.php?accion=perfil">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña:</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Ingresa tu nueva contraseña">
                            <div class="form-text">
                                <strong>Requisitos de seguridad:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Mínimo 8 caracteres</li>
                                    <li>Al menos una letra mayúscula</li>
                                    <li>Al menos una letra minúscula</li>
                                    <li>Al menos un número</li>
                                    <li>Al menos un carácter especial (!@#$%^&*)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                   placeholder="Confirma tu nueva contraseña">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cambiar Contraseña
                        </button>
                    </form>
                </div>
            </div>

            <!-- Información de la Cuenta -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información de la Cuenta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>ID de Usuario:</strong> <?= $_SESSION['usuario_id'] ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Rol:</strong> 
                            <?php
                            $roles = [1 => 'Usuario', 2 => 'Estudiante', 3 => 'Docente', 4 => 'Administrador'];
                            echo $roles[$_SESSION['rol_id']] ?? 'Desconocido';
                            ?>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <?php if (isset($usuario['FECHA_REGISTRO'])): ?>
                            <div class="col-md-6">
                                <strong>Fecha de Registro:</strong> 
                                <?= date('d/m/Y', strtotime($usuario['FECHA_REGISTRO'])) ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-6">
                            <strong>Tiempo de Acceso:</strong> 
                            <?= $tiempo_acceso ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Académica (Solo para Estudiantes) -->
            <?php if ($_SESSION['rol_id'] == 2): // Si es estudiante ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap"></i> Información Académica
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Email Corporativo:</strong>
                                    <div class="form-control-plaintext">
                                        <?= htmlspecialchars($usuario['EMAIL_CORPORATIVO'] ?? 'No asignado') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Código de Estudiante:</strong>
                                    <div class="form-control-plaintext">
                                        <?= htmlspecialchars($usuario['CODIGO_ESTUDIANTE'] ?? 'No asignado') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Condición Académica:</strong>
                                    <div class="form-control-plaintext">
                                        <?php
                                        $condicion = $usuario['CONDICION'] ?? 'No definida';
                                        $clase_badge = '';
                                        switch(strtolower($condicion)) {
                                            case 'aprobado':
                                                $clase_badge = 'bg-success';
                                                break;
                                            case 'desaprobado':
                                                $clase_badge = 'bg-danger';
                                                break;
                                            case 'en curso':
                                                $clase_badge = 'bg-warning';
                                                break;
                                            default:
                                                $clase_badge = 'bg-secondary';
                                        }
                                        ?>
                                        <span class="badge <?= $clase_badge ?>"><?= htmlspecialchars($condicion) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Carrera/Programa:</strong>
                                    <div class="form-control-plaintext">
                                        <?= htmlspecialchars($usuario['CARRERA'] ?? 'No asignada') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            La información académica es administrada por el sistema y no puede ser modificada por el estudiante.
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>/public/index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Validación de confirmación de contraseña en tiempo real
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Las contraseñas no coinciden');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
    }
});

// Validación de contraseña segura en tiempo real
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const requirements = [
        { regex: /.{8,}/, text: 'Mínimo 8 caracteres' },
        { regex: /[A-Z]/, text: 'Una letra mayúscula' },
        { regex: /[a-z]/, text: 'Una letra minúscula' },
        { regex: /[0-9]/, text: 'Un número' },
        { regex: /[^A-Za-z0-9]/, text: 'Un carácter especial' }
    ];
    
    let isValid = true;
    requirements.forEach(req => {
        if (!req.regex.test(password)) {
            isValid = false;
        }
    });
    
    if (isValid && password.length > 0) {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
    } else if (password.length > 0) {
        this.classList.remove('is-valid');
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-valid', 'is-invalid');
    }
});
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>