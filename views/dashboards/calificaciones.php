<?php
require_once '../../config/constants.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 3) {
    header('Location: ' . BASE_URL . '/public/index.php?accion=login');
    exit;
}

require_once BASE_PATH . '/models/Clase.php';
require_once BASE_PATH . '/models/Unidad.php';
require_once BASE_PATH . '/models/Nota.php';

$claseModel = new Clase();
$unidadModel = new Unidad();
$notaModel = new Nota();

$docenteId = $_SESSION['usuario_id'];
$clases = $claseModel->obtenerPorMentor($docenteId);
$unidades = $unidadModel->obtenerTodos();

$seleccionadoClase = $_POST['ID_CLASE'] ?? null;
$seleccionadoUnidad = $_POST['ID_UNIDAD'] ?? null;
$estudiantes = [];

if ($seleccionadoClase && $seleccionadoUnidad) {
    $estudiantes = $notaModel->obtenerEstudiantesConNotas($seleccionadoClase, $seleccionadoUnidad, $docenteId);
}

include BASE_PATH . '/views/components/head.php';
include BASE_PATH . '/views/components/header.php';
?>

<div class="contenido-dashboard">
    <h2>Registrar Calificaciones</h2>

    <form method="POST">
        <label>Selecciona Clase:</label>
        <select name="ID_CLASE" required>
            <option value="">-- Selecciona --</option>
            <?php foreach ($clases as $clase): ?>
                <option value="<?= $clase['ID_CLASE'] ?>" <?= ($clase['ID_CLASE'] == $seleccionadoClase ? 'selected' : '') ?>>
                    <?= $clase['curso'] ?> - <?= $clase['HORARIO'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Unidad:</label>
        <select name="ID_UNIDAD" required>
            <option value="">-- Selecciona --</option>
            <?php foreach ($unidades as $unidad): ?>
                <option value="<?= $unidad['ID_UNIDAD'] ?>" <?= ($unidad['ID_UNIDAD'] == $seleccionadoUnidad ? 'selected' : '') ?>>
                    <?= $unidad['NOMBRE'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Cargar estudiantes</button>
    </form>

    <?php if (!empty($estudiantes)): ?>
        <form method="POST" action="guardar_calificaciones.php">
            <input type="hidden" name="ID_CLASE" value="<?= $seleccionadoClase ?>">
            <input type="hidden" name="ID_UNIDAD_
