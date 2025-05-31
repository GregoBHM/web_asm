<?php
require_once '../../config/constants.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 3) {
    header('Location: ' . BASE_URL . '/public/index.php?accion=login');
    exit;
}

require_once BASE_PATH . '/models/Clase.php';
require_once BASE_PATH . '/models/Curso.php';
require_once BASE_PATH . '/models/Aula.php';

$claseModel = new Clase();
$cursoModel = new Curso();
$aulaModel = new Aula();

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Clase no encontrada'); window.location.href='sesiones.php';</script>";
    exit;
}

$clase = $claseModel->obtenerPorId($id);
if (!$clase) {
    echo "<script>alert('Clase no encontrada'); window.location.href='sesiones.php';</script>";
    exit;
}

$cursos = $cursoModel->obtenerTodos();
$aulas = $aulaModel->obtenerTodos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claseModel->actualizarClase($id, $_POST);
    echo "<script>alert('Clase actualizada correctamente'); window.location.href='sesiones.php';</script>";
    exit;
}

include BASE_PATH . '/views/components/head.php';
include BASE_PATH . '/views/components/header.php';
?>

<div class="contenido-dashboard">
    <h2>Editar Clase</h2>
    <form method="POST">
        <label>Curso:</label>
        <select name="ID_CURSO" required>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= $curso['ID_CURSO'] ?>" <?= $clase['ID_CURSO'] == $curso['ID_CURSO'] ? 'selected' : '' ?>>
                    <?= $curso['NOMBRE'] ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Aula:</label>
        <select name="ID_AULA" required>
            <?php foreach ($aulas as $aula): ?>
                <option value="<?= $aula['ID_AULA'] ?>" <?= $clase['ID_AULA'] == $aula['ID_AULA'] ? 'selected' : '' ?>>
                    <?= $aula['NOMBRE'] ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Horario:</label>
        <input type="text" name="HORARIO" value="<?= $clase['HORARIO'] ?>" required><br><br>

        <label>Fecha Inicio:</label>
        <input type="datetime-local" name="FECHA_INICIO" value="<?= date('Y-m-d\TH:i', strtotime($clase['FECHA_INICIO'])) ?>" required><br><br>

        <label>Fecha Fin:</label>
        <input type="datetime-local" name="FECHA_FIN" value="<?= date('Y-m-d\TH:i', strtotime($clase['FECHA_FIN'])) ?>" required><br><br>

        <label>Capacidad:</label>
        <input type="number" name="CAPACIDAD" value="<?= $clase['CAPACIDAD'] ?>" required><br><br>

        <label>Razón:</label>
        <input type="text" name="RAZON" value="<?= $clase['RAZON'] ?>"><br><br>

        <button type="submit">Guardar cambios</button>
    </form>
</div>
