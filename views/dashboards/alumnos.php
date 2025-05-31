<?php
require_once '../../config/constants.php';
require_once BASE_PATH . '/models/Clase.php';

session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 3) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$docenteId = $_SESSION['usuario_id'];
$claseModel = new Clase();

$cursosClases = $claseModel->listarClasesPorDocente($docenteId);

// Obtener filtros seleccionados
$filtroCurso = $_GET['curso'] ?? '';
$filtroClase = $_GET['clase'] ?? '';

$estudiantes = $claseModel->listarEstudiantesFiltrados($docenteId, $filtroCurso, $filtroClase);

include '../components/head.php';
include '../components/header.php';
?>

<div class="contenido-dashboard">
    <h2>Mis Estudiantes</h2>

    <form method="GET" action="alumnos.php">
        <label>Filtrar por curso:</label>
        <select name="curso" onchange="this.form.submit()">
            <option value="">-- Todos --</option>
            <?php foreach ($cursosClases as $c): ?>
                <option value="<?= $c['CURSO'] ?>" <?= ($filtroCurso == $c['CURSO'] ? 'selected' : '') ?>>
                    <?= $c['CURSO'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Filtrar por clase:</label>
        <select name="clase" onchange="this.form.submit()">
            <option value="">-- Todas --</option>
            <?php foreach ($cursosClases as $c): ?>
                <option value="<?= $c['ID_CLASE'] ?>" <?= ($filtroClase == $c['ID_CLASE'] ? 'selected' : '') ?>>
                    <?= $c['CURSO'] ?> - <?= $c['HORARIO'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <br>

    <?php if (!empty($estudiantes)): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Curso</th>
                    <th>Clase</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $e): ?>
                    <tr>
                        <td><?= $e['NOMBRE'] . ' ' . $e['APELLIDO'] ?></td>
                        <td><?= $e['EMAIL'] ?></td>
                        <td><?= $e['CURSO'] ?></td>
                        <td><?= $e['HORARIO'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron estudiantes para el filtro aplicado.</p>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
