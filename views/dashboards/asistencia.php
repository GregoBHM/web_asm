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
$clases = $claseModel->listarClasesPorDocente($docenteId);

$estudiantes = [];
if (isset($_GET['clase_id'])) {
    $estudiantes = $claseModel->listarEstudiantesPorClase($_GET['clase_id']);
}

include '../components/head.php';
include '../components/header.php';
?>

<div class="contenido-dashboard">
    <h2>Registrar Asistencia</h2>

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

    <?php if (!empty($estudiantes)): ?>
        <form method="POST" action="guardar_asistencia.php">
            <input type="hidden" name="clase_id" value="<?= $_GET['clase_id'] ?>">
            <table border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <th>Estudiante</th>
                    <th>Asistió</th>
                </tr>
                <?php foreach ($estudiantes as $est): ?>
                    <tr>
                        <td><?= $est['NOMBRE'] . ' ' . $est['APELLIDO'] ?></td>
                        <td>
                            <input type="checkbox" name="asistencias[<?= $est['ID_ESTUDIANTE'] ?>]" value="1" checked>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <button type="submit">Guardar asistencia</button>
        </form>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
