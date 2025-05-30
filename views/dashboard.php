<?php
require_once '../config/constants.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '/public/index.php?accion=login');
    exit;
}
$rol = (int) $_SESSION['rol_id'];

include BASE_PATH . '/views/components/head.php'; 
include BASE_PATH . '/views/components/header.php';
?>

<div class="submenu-rol">
    <?php if ($rol === 2): ?>
        <a href="<?= BASE_URL ?>/views/dashboards/clases.php">Mis Clases</a>
        <a href="<?= BASE_URL ?>/views/dashboards/mentores.php">Mis Mentores</a>
        <a href="<?= BASE_URL ?>/views/dashboards/calificaciones.php">Calificaciones</a>
    <?php elseif ($rol === 3): ?>
        <a href="<?= BASE_URL ?>/views/dashboards/alumnos.php">Alumnos</a>
        <a href="<?= BASE_URL ?>/views/dashboards/sesiones.php">Sesiones</a>
    <?php elseif ($rol === 4): ?>
        <a href="<?= BASE_URL ?>/views/dashboards/usuarios.php">Gestión de Usuarios</a>
        <a href="<?= BASE_URL ?>/views/dashboards/reportes.php">Reportes</a>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>/views/dashboards/perfil.php">Mi Perfil</a>
</div>

<div class="contenido-dashboard">
    <div class="panel-central">
        <h1>
            <?php
            switch ($rol) {
                case 2: echo 'Bienvenido Estudiante'; break; //Redirigir al panel de su rol
                case 3: echo 'Panel del Docente'; break;
                case 4: echo 'Panel del Administrador'; break;
                default: echo 'Bienvenido al Sistema'; break;
            }
            ?>
        </h1>
        <p>Explora las funcionalidades disponibles para tu rol.</p>
    </div>
</div>
