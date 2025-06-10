<?php
require_once '../../config/constants.php';
require_once BASE_PATH . '/models/Usuario.php';

session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] == 0) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$usuarioModel = new Usuario();
$usuario = $usuarioModel->obtenerPorId($_SESSION['usuario_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $usuarioModel->actualizarPerfil($_SESSION['usuario_id'], $nombre, $apellido, $email, $password);

    echo "<script>alert('Perfil actualizado correctamente'); window.location.href='perfil.php';</script>";
    exit;
}

include '../components/head.php';
include '../components/header.php';
?>

<div class="contenido-dashboard">
    <h2>Mi Perfil</h2>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $usuario['NOMBRE'] ?>" required><br><br>

        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?= $usuario['APELLIDO'] ?>" required><br><br>

        <label>Correo:</label>
        <input type="email" name="email" value="<?= $usuario['EMAIL'] ?>" required><br><br>

        <label>Nueva contraseña (opcional):</label>
        <input type="password" name="password"><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>
</div>

<?php include '../components/footer.php'; ?>
