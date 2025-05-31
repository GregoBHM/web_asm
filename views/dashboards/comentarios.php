<?php
require_once '../../config/constants.php';
require_once '../../models/Clase.php';
require_once '../../models/Estudiante.php';
require_once '../../models/Comentario.php';

session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$docenteId = $_SESSION['usuario_id'];
$claseModel = new Clase();
$comentarioModel = new Comentario();
$estudianteModel = new Estudiante();

$clases = $claseModel->listarClasesPorDocente($docenteId);
$comentarios = [];
$estudiantes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEstudiante = $_POST['estudiante_id'];
    $puntuacion = $_POST['puntuacion'];
    $comentarioModel->registrarComentario($docenteId, $idEstudiante, $puntuacion);
}

if (isset($_GET['clase_id'])) {
    $claseId = $_GET['clase_id'];
    $estudiantes = $estudianteModel->listarPorClase($claseId);
}

include '../components/head.php';
include '../components/header.php';
?>

<div class="contenido-dashboard">
    <h2>Comentarios y Valoraciones</h2>

    <form method="GET" action="">
        <label for="clase_id">Seleccionar clase:</label>
        <select name="clase_id" id="clase_id" onchange="this.form.submit()">
            <option value="">-- Selecciona --</option>
            <?php foreach ($clases as $c): ?>
                <option value="<?= $c['ID_CLASE'] ?>" <?= (isset($_GET['clase_id']) && $_GET['clase_id'] == $c['ID_CLASE']) ? 'selected' : '' ?>>
                    <?= $c['NOMBRE_CURSO'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (!empty($estudiantes)): ?>
        <h3>Estudiantes inscritos:</h3>
        <form method="POST">
            <table border="1" cellpadding="6" cellspacing="0">
                <tr>
                    <th>Estudiante</th>
                    <th>Puntuación (1 a 5)</th>
                    <th>Acción</th>
                </tr>
                <?php foreach ($estudiantes as $e): ?>
                    <tr>
                        <td><?= $e['NOMBRE'] . ' ' . $e['APELLIDO'] ?></td>
                        <td>
                            <input type="number" name="puntuacion" min="1" max="5" required>
                            <input type="hidden" name="estudiante_id" value="<?= $e['ID_ESTUDIANTE'] ?>">
                        </td>
                        <td><button type="submit">Enviar</button></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </form>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
