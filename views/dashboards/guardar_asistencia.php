<?php
require_once '../../config/constants.php';
require_once '../../models/Asistencia.php';

session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claseId = $_POST['clase_id'];
    $asistencias = $_POST['asistencias'] ?? [];

    $modelo = new Asistencia();
    $modelo->guardarAsistencia($claseId, $asistencias);

    echo "<script>alert('Asistencia registrada correctamente');window.location.href='asistencia.php';</script>";
} else {
    header('Location: asistencia.php');
}
