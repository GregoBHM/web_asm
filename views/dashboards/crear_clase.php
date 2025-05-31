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

$cursoModel = new Curso();
$aulaModel = new Aula();

$cursos = $cursoModel->obtenerTodos();
$aulas = $aulaModel->obtenerTodos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claseModel = new Clase();
    $claseModel->insertarClase($_POST, $_SESSION['usuario_id']);
    echo "<script>alert('Clase registrada correctamente'); window.location.href='sesiones.php';</script>";
    exit;
}

include BASE_PATH . '/views/components/head.php';
include BASE_PATH . '/views/components/header.php';
?>

<div class="contenido-dashboard">
    <h2>Registrar Nueva Clase</h2>
    <form method="POST">
        <label>Curso:</label>
        <select name="ID_CURSO" required>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= $curso['ID_CURSO'] ?>"><?= $curso['NOMBRE'] ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Aula:</label>
        <select name="ID_AULA" required>
            <?php foreach ($aulas as $aula): ?>
                <option value="<?= $aula['ID_AULA'] ?>"><?= $aula['NOMBRE'] ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Horario:</label>
        <input type="text" name="HORARIO" required><br><br>

        <label>Fecha Inicio:</label>
        <input type="datetime-local" name="FECHA_INICIO" required><br><br>

        <label>Fecha Fin:</label>
        <input type="datetime-local" name="FECHA_FIN" required><br><br>

        <label>Capacidad:</label>
        <input type="number" name="CAPACIDAD" required><br><br>

        <label>Razón (opcional):</label>
        <input type="text" name="RAZON"><br><br>

        <button type="submit">Registrar Clase</button>
    </form>
</div>
