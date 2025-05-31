<?php
require_once '../../config/constants.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 3) {
    header('Location: ' . BASE_URL . '/public/index.php?accion=login');
    exit;
}

require_once BASE_PATH . '/models/Nota.php';

$notaModel = new Nota();
$id_usuario = $_SESSION['usuario_id'];
$ip = $_SERVER['REMOTE_ADDR'];
$id_unidad = $_POST['ID_UNIDAD'] ?? null;

foreach ($_POST['calificacion'] as $id_registro => $nota) {
    $tipo = $_POST['tipo_nota'][$id_registro] ?? '';
    $obs = $_POST['observacion'][$id_registro] ?? '';
    
    // Validación simple
    if ($nota !== '' && $tipo !== '') {
        $notaModel->guardar($id_registro, $id_unidad, $tipo, $nota, $id_usuario, $ip, $obs);
    }
}

echo "<script>alert('Notas guardadas correctamente'); window.location.href='calificaciones.php';</script>";
exit;
