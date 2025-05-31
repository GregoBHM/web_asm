<?php
require_once '../../config/constants.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 2) {
    header('Location: ' . BASE_URL . '/public/index.php?accion=login');
    exit;
}

include BASE_PATH . '/views/components/head.php';
include BASE_PATH . '/views/components/header.php';

require_once BASE_PATH . '/models/Clase.php';
require_once BASE_PATH . '/models/Usuario.php';

$usuarioModel = new Usuario();
$id_estudiante = $usuarioModel->obtenerIdEstudiante($_SESSION['usuario_id']);

$claseModel = new Clase();
$clases = $claseModel->obtenerClasesPorEstudiante($id_estudiante);
?>

<div class="contenido-dashboard">
    <h2>Mis Clases Inscritas</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Horario</th>
                <th>Mentor</th>
                <th>Inicio</th>
                <th>Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clases as $clase): ?>
                <tr>
                    <td><?= $clase['CURSO'] ?></td>
                    <td><?= $clase['HORARIO'] ?></td>
                    <td><?= $clase['MENTOR'] ?></td>
                    <td><?= $clase['FECHA_INICIO'] ?></td>
                    <td><?= $clase['FECHA_FIN'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
