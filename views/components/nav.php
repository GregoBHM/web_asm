<?php if (isset($_SESSION['rol_id'])): ?>
    <div class="submenu-rol">
        <?php if ($_SESSION['rol_id'] === 2): ?>
            <a href="<?= BASE_URL ?>/views/estudiante/clases.php">Mis Clases</a>
            <a href="<?= BASE_URL ?>/views/estudiante/mentores.php">Mis Mentores</a>
            <a href="<?= BASE_URL ?>/views/estudiante/calificaciones.php">Calificaciones</a>

        <?php elseif ($_SESSION['rol_id'] === 3): ?>
            <a href="<?= BASE_URL ?>/views/dashboards/alumnos.php">Alumnos</a>
            <a href="<?= BASE_URL ?>/views/dashboards/sesiones.php">Sesiones</a>

        <?php elseif ($_SESSION['rol_id'] === 4): ?>
            <a href="<?= BASE_URL ?>/views/dashboards/usuarios.php">Gestión de Usuarios</a>
            <a href="<?= BASE_URL ?>/views/dashboards/reportes.php">Reportes</a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/views/usuario/perfil.php">Mi Perfil</a>
    </div>
<?php endif; ?>
