<?php
require_once '../../config/constants.php';
require_once BASE_PATH . '/models/Clase.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 3) {
    header('Location: ' . BASE_URL . '/public/index.php?accion=login');
    exit;
}

$claseId = $_GET['id'] ?? null;
if (!$claseId) {
    die("Clase no especificada.");
}

$claseModel = new Clase();
$estudiantes = $claseModel->listarEstudiantesFiltrados($_SESSION['usuario_id'], '', $claseId);

include BASE_PATH . '/views/components/head.php';
include BASE_PATH . '/views/components/header.php';
?>

<div class="contenido-dashboard">
    <h2>Estudiantes Inscritos</h2>
    <a href="sesiones.php" class="btn">← Volver</a>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Curso</th>
                <th>Horario</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($estudiantes as $alumno): ?>
                <tr>
                    <td><?= $alumno['NOMBRE'] ?></td>
                    <td><?= $alumno['APELLIDO'] ?></td>
                    <td><?= $alumno['EMAIL'] ?></td>
                    <td><?= $alumno['CURSO'] ?></td>
                    <td><?= $alumno['HORARIO'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
