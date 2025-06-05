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
include BASE_PATH . '/views/components/nav.php';
?>

<div class="contenido-dashboard">
    <div class="panel-central">
        <h1>
            <?php
            switch ($rol) {
                case 2: echo 'Bienvenido Estudiante'; break;
                case 3: echo 'Panel del Docente'; break;
                case 4: echo 'Panel del Administrador'; break;
                default: echo 'Bienvenido al Sistema'; break;
            }
            ?>
        </h1>
        <p>Explora las funcionalidades disponibles para tu rol.</p>
    </div>
</div>
