<?php
require_once '../../config/constants.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 3) {
    header('Location: ' . BASE_URL . '/public/index.php?accion=login');
    exit;
}

require_once BASE_PATH . '/models/Clase.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('Clase no encontrada'); window.location.href='sesiones.php';</script>";
    exit;
}

$claseModel = new Clase();
$claseModel->eliminarClase($id);

echo "<script>alert('Clase eliminada correctamente'); window.location.href='sesiones.php';</script>";
exit;
