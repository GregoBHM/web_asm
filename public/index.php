<?php
session_start();

if (isset($_SESSION['usuario_id']) && isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 'administrador':
            header('Location: ../views/dashboard/admin.php');
            exit;
        case 'docente':
            header('Location: ../views/dashboard/docente.php');
            exit;
        case 'estudiante':
            header('Location: ../views/dashboard/estudiante.php');
            exit;
        case 'visitante':
        default:
            header('Location: ../views/dashboard/visitante.php');
            exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include("../views/components/head.php"); ?>
    <title>Sistema de Mentoría Académica</title>
</head>
<body>

    <?php include("../views/components/header.php"); ?>

    <main style="text-align: center; padding: 100px 20px;">
        <h1>Bienvenido al Sistema de Mentoría Académica</h1>
        <p>Una plataforma que te conecta con mentores para mejorar tu rendimiento académico.</p>
    </main>

    <?php include("../views/components/footer.php"); ?>

</body>
</html>
