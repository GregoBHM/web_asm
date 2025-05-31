<?php
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 0;
    }
    .register-container {
        width: 100%;
        max-width: 400px;
        margin: 100px auto;
        background: #fff;
        padding: 30px 40px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .register-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #002147;
    }
    .register-container input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .register-container button {
        width: 100%;
        background-color: #002147;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
        cursor: pointer;
    }
    .register-container .login-link {
        text-align: center;
        margin-top: 15px;
    }
    .register-container .login-link a {
        color: #007bff;
        text-decoration: none;
    }
</style>

<div class="register-container">
    <h2>Crear cuenta</h2>
    <form method="post" action="<?= BASE_URL ?>/public/index.php?accion=procesar_registro">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>

    <div class="login-link">
        ¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/public/index.php?accion=login">Inicia sesión aquí</a>
    </div>
</div>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>
