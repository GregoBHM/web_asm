<?php if (isset($_SESSION['rol_id'])): ?>
    <div class="submenu-rol">
        <?php if ($_SESSION['rol_id'] === 2): ?>
            <a href="<?= BASE_URL ?>/public/index.php?accion=clases">Mis Clases</a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=solicitar">Mis Mentores</a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=calificaciones">Calificaciones</a>

        <?php elseif ($_SESSION['rol_id'] === 3): ?>
            <a href="<?= BASE_URL ?>/public/index.php?accion=alumnos">Alumnos</a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=sesiones">Sesiones</a>

        <?php elseif ($_SESSION['rol_id'] === 4): ?>
            <a href="<?= BASE_URL ?>/public/index.php?accion=usuarios">Gestión de Usuarios</a>
            <a href="<?= BASE_URL ?>/public/index.php?accion=reportes">Reportes</a>
        <?php endif; ?>

        <?php if ($_SESSION['rol_id'] === 1): ?>
            <a href="<?= BASE_URL ?>/public/index.php?accion=vincular">Vincularme a la UPT</a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/public/index.php?accion=perfil">Mi Perfil</a>
    </div>
<?php endif; ?>