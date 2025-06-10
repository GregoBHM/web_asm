<?php 
require_once __DIR__ . '/../../config/constants.php'; 
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/public/index.php">
            <div class="university-logo">
                <img src="<?= BASE_URL ?>/public/img/ams.png" alt="Logo UPT" class="logo-img">
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fas fa-bars text-white"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'index' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'mentores' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?accion=mentores">
                        <i class="fas fa-chalkboard-teacher"></i> Mentores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'estudiantes' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?accion=alumnos">
                        <i class="fas fa-user-graduate"></i> Estudiantes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'novedades' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?accion=anuncios">
                        <i class="fas fa-bullhorn"></i> Novedades
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'faq' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?accion=faq">
                        <i class="fas fa-question-circle"></i> F.A.Q
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'testimonios' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?accion=testimonios">
                        <i class="fas fa-comments"></i> Testimonios
                    </a>
                </li>
            </ul>
            <!-- Botón login/logout dinámico -->
            <div class="d-flex">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="#" onclick="confirmarCerrarSesion(event, '<?= BASE_URL ?>/public/index.php?accion=cerrar')" class="btn btn-danger d-flex align-items-center">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/public/index.php?accion=login" class="btn btn-login d-flex align-items-center">
                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
