<?php
require_once '../../config/constants.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 3) {
    header('Location: ' . BASE_URL . '/public/index.php?accion=login');
    exit;
}

include BASE_PATH . '/views/components/head.php';
include BASE_PATH . '/views/components/header.php';

// Lógica para obtener clases del mentor
require_once BASE_PATH . '/models/Clase.php';
$claseModel = new Clase();
$clases = $claseModel->obtenerPorMentor($_SESSION['usuario_id']);
?>

<div class="contenido-dashboard">
    <h2>Mis Clases Programadas</h2>
    <a href="crear_clase.php" class="btn">+ Nueva Clase</a>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Horario</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clases as $clase): ?>
                <tr>
                    <td><?= $clase['curso'] ?></td>
                    <td><?= $clase['HORARIO'] ?></td>
                    <td><?= $clase['FECHA_INICIO'] ?></td>
                    <td><?= $clase['FECHA_FIN'] ?></td>
                    <td><?= $clase['ESTADO'] ? 'Activa' : 'Inactiva' ?></td>
                    <td>
                        <a href="editar_clase.php?id=<?= $clase['ID_CLASE'] ?>">Editar</a> |
                        <a href="eliminar_clase.php?id=<?= $clase['ID_CLASE'] ?>" onclick="return confirm('¿Estás seguro de eliminar esta clase?')">Eliminar</a> |
                        <a href="ver_estudiantes.php?id=<?= $clase['ID_CLASE'] ?>">Ver Alumnos</a>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
