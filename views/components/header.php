<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<header class="header">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
        <div class="logo">
            <a href="<?= BASE_URL ?>/public/index.php">
                <img src="<?= BASE_URL ?>/public/img/upt.png" alt="UPT">
            </a>
        </div>

        <nav class="menu d-flex flex-wrap align-items-center gap-3">
            <a href="<?= BASE_URL ?>/public/index.php" class="nav-link text-white">
                <i class="fas fa-home"></i> Inicio
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=mentores" class="nav-link text-white">
                <i class="fas fa-chalkboard-teacher"></i> Mentores
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=alumnos" class="nav-link text-white">
                <i class="fas fa-user-graduate"></i> Estudiantes
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=anuncios" class="nav-link text-white">
                <i class="fas fa-bullhorn"></i> Novedades
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=faq" class="nav-link text-white">
                <i class="fas fa-question-circle"></i> F.A.Q
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=testimonios" class="nav-link text-white">
                <i class="fas fa-comment-dots"></i> Testimonios
            </a>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="#" onclick="confirmarCerrarSesion(event, '<?= BASE_URL ?>/public/index.php?accion=cerrar')" class="nav-link text-danger fw-bold">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/public/index.php?accion=login" class="nav-link text-success fw-bold">
                    <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                </a>
            <?php endif; ?>
        </nav>
    </div>
</header>
