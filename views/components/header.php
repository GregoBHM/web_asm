<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<header class="header position-fixed w-100 top-0 shadow-lg">
    <div class="container-fluid px-3 px-lg-4 d-flex justify-content-between align-items-center flex-wrap">
        <div class="logo">
            <a href="<?= BASE_URL ?>/public/index.php" class="logo-link d-flex align-items-center">
                <img src="<?= BASE_URL ?>/public/img/upt.png" alt="UPT" class="logo-img">
                <span class="logo-text d-none d-md-inline ms-2">UPT-AMS</span>
            </a>
        </div>

        <!-- Botón hamburguesa para móviles -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <nav class="menu d-none d-lg-flex flex-wrap align-items-center gap-2" id="desktopMenu">
            <a href="<?= BASE_URL ?>/public/index.php" class="nav-link text-white position-relative">
                <i class="fas fa-home nav-icon"></i>
                <span class="nav-text">Inicio</span>
                <span class="nav-underline"></span>
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=mentores" class="nav-link text-white position-relative">
                <i class="fas fa-chalkboard-teacher nav-icon"></i>
                <span class="nav-text">Mentores</span>
                <span class="nav-underline"></span>
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=alumnos" class="nav-link text-white position-relative">
                <i class="fas fa-user-graduate nav-icon"></i>
                <span class="nav-text">Estudiantes</span>
                <span class="nav-underline"></span>
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=anuncios" class="nav-link text-white position-relative">
                <i class="fas fa-bullhorn nav-icon"></i>
                <span class="nav-text">Novedades</span>
                <span class="nav-underline"></span>
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=faq" class="nav-link text-white position-relative">
                <i class="fas fa-question-circle nav-icon"></i>
                <span class="nav-text">F.A.Q</span>
                <span class="nav-underline"></span>
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=testimonios" class="nav-link text-white position-relative">
                <i class="fas fa-comment-dots nav-icon"></i>
                <span class="nav-text">Testimonios</span>
                <span class="nav-underline"></span>
            </a>
            
            <div class="nav-separator mx-2"></div>
            
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="#" onclick="confirmarCerrarSesion(event, '<?= BASE_URL ?>/public/index.php?accion=cerrar')" class="nav-link text-danger fw-bold logout-btn position-relative">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <span class="nav-text">Cerrar sesión</span>
                    <span class="nav-underline bg-danger"></span>
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/public/index.php?accion=login" class="nav-link text-success fw-bold login-btn position-relative">
                    <i class="fas fa-sign-in-alt nav-icon"></i>
                    <span class="nav-text">Iniciar sesión</span>
                    <span class="nav-underline bg-success"></span>
                </a>
            <?php endif; ?>
        </nav>

        <!-- Menú móvil -->
        <nav class="collapse navbar-collapse d-lg-none" id="mobileMenu">
            <div class="mobile-menu-content">
                <a href="<?= BASE_URL ?>/public/index.php" class="mobile-nav-link text-white">
                    <i class="fas fa-home me-3"></i>Inicio
                </a>
                <a href="<?= BASE_URL ?>/public/index.php?accion=mentores" class="mobile-nav-link text-white">
                    <i class="fas fa-chalkboard-teacher me-3"></i>Mentores
                </a>
                <a href="<?= BASE_URL ?>/public/index.php?accion=alumnos" class="mobile-nav-link text-white">
                    <i class="fas fa-user-graduate me-3"></i>Estudiantes
                </a>
                <a href="<?= BASE_URL ?>/public/index.php?accion=anuncios" class="mobile-nav-link text-white">
                    <i class="fas fa-bullhorn me-3"></i>Novedades
                </a>
                <a href="<?= BASE_URL ?>/public/index.php?accion=faq" class="mobile-nav-link text-white">
                    <i class="fas fa-question-circle me-3"></i>F.A.Q
                </a>
                <a href="<?= BASE_URL ?>/public/index.php?accion=testimonios" class="mobile-nav-link text-white">
                    <i class="fas fa-comment-dots me-3"></i>Testimonios
                </a>
                
                <div class="mobile-separator my-3"></div>
                
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="#" onclick="confirmarCerrarSesion(event, '<?= BASE_URL ?>/public/index.php?accion=cerrar')" class="mobile-nav-link text-danger fw-bold">
                        <i class="fas fa-sign-out-alt me-3"></i>Cerrar sesión
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/public/index.php?accion=login" class="mobile-nav-link text-success fw-bold">
                        <i class="fas fa-sign-in-alt me-3"></i>Iniciar sesión
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>