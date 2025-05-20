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
    .login-container {
        width: 100%;
        max-width: 400px;
        margin: 100px auto;
        background: #fff;
        padding: 30px 40px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .login-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #002147;
    }
    .login-container input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .login-container button {
        width: 100%;
        background-color: #002147;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
        cursor: pointer;
    }
    .login-container .register-link {
        text-align: center;
        margin-top: 15px;
    }
    .login-container .register-link a {
        color: #007bff;
        text-decoration: none;
    }
    .social-buttons button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: none;
        margin-top: 10px;
        cursor: pointer;
        font-size: 14px;
    }
    .google { background-color: #db4437; color: white; }
    .discord { background-color: #5865f2; color: white; }
    .microsoft { background-color: #2f2f2f; color: white; }
    .social-buttons i {
        font-size: 18px;
    }
</style>

<div class="login-container">
    <h2>Iniciar sesión</h2>
    <form method="post" action="<?= BASE_URL ?>/public/index.php?accion=procesar_login">
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
    </form>

    <div class="register-link">
        ¿No tienes cuenta? <a href="<?= BASE_URL ?>/public/index.php?accion=registro">Regístrate aquí</a>
    </div>

    <hr>
    <div class="social-buttons">
        <form action="<?= BASE_URL ?>/auth/google.php" method="get">
            <button class="google" type="submit">
                <i class="fab fa-google"></i> Continuar con Google
            </button>
        </form>
        <form action="<?= BASE_URL ?>/auth/microsoft" method="post">
            <button class="microsoft"><i class="fab fa-microsoft"></i> Continuar con Microsoft</button>
        </form>
    </div>
</div>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>
