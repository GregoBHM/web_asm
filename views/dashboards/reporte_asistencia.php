<?php
require_once '../../config/constants.php';
require_once '../../models/Clase.php';
require_once '../../models/Asistencia.php';

session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$docenteId = $_SESSION['usuario_id'];
$claseModel = new Clase();
$asistenciaModel = new Asistencia();

$clases = $claseModel->listarClasesPorDocente($docenteId);
$registros = [];

if (isset($_GET['clase_id'])) {
    $claseId = $_GET['clase_id'];
    $registros = $asistenciaModel->obtenerAsistenciaPorClase($claseId);
}

include '../components/head.php';
include '../components/header.php';
?>

<div class="contenido-dashboard">
    <h2>Reporte de Asistencia</h2>

    <form method="GET" action="">
        <label for="clase_id">Selecciona una clase:</label>
        <select name="clase_id" id="clase_id" onchange="this.form.submit()">
            <option value="">-- Selecciona --</option>
            <?php foreach ($clases as $clase): ?>
                <option value="<?= $clase['ID_CLASE'] ?>" <?= (isset($_GET['clase_id']) && $_GET['clase_id'] == $clase['ID_CLASE']) ? 'selected' : '' ?>>
                    <?= $clase['NOMBRE_CURSO'] ?> (<?= $clase['HORARIO'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (!empty($registros)): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>Estudiante</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
            <?php foreach ($registros as $r): ?>
                <tr>
                    <td><?= $r['NOMBRE'] . ' ' . $r['APELLIDO'] ?></td>
                    <td><?= date('Y-m-d', strtotime($r['FECHA'])) ?></td>
                    <td><?= $r['ESTADO'] == 1 ? 'Asistió' : 'Faltó' ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif (isset($_GET['clase_id'])): ?>
        <p>No hay registros de asistencia para esta clase.</p>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
