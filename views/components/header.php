<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<header class="header">
    <div class="container">
        <div class="logo">
            <a href="<?= BASE_URL ?>/public/index.php">
                <img src="<?= BASE_URL ?>/public/img/upt.png" alt="UPT">
            </a>
        </div>

        <nav class="menu">
            <a href="<?= BASE_URL ?>/public/index.php"><i class="fas fa-home"></i>Inicio</a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=mentores"><i class="fas fa-chalkboard-teacher"></i>Mentores destacados</a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=alumnos"><i class="fas fa-user-graduate"></i>Estudiantes destacados</a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=anuncios"><i class="fas fa-bullhorn"></i>Novedades</a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=faq"><i class="fas fa-question-circle"></i>F.A.Q</a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=testimonios"><i class="fas fa-comment-dots"></i>Testimonios</a>

            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="#" onclick="confirmarCerrarSesion(event, '<?= BASE_URL ?>/public/index.php?accion=cerrar')">
                    <i class="fas fa-sign-out-alt"></i>Cerrar sesión
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/public/index.php?accion=login"><i class="fas fa-sign-in-alt"></i>Iniciar sesión</a>
            <?php endif; ?>
        </nav>
    </div>
</header>