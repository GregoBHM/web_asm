<?php
require_once BASE_PATH . '/models/Usuario.php';

class AuthController {

    public function mostrarLogin() {
        require BASE_PATH . '/views/login.php';
    }

    public function procesarLogin() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $usuario = new Usuario();
        $datos = $usuario->verificarCredenciales($email, $password);

        if ($datos) {
            session_start();
            $_SESSION['usuario_id'] = $datos['ID_USUARIO'];
            $_SESSION['rol_id'] = (int)$datos['ID_ROL']; 
            $_SESSION['rol_nombre'] = $datos['ROL'];   

            header('Location: ' . BASE_URL . '/public/index.php');
        } else {
            echo "<script>alert('Credenciales incorrectas');window.location.href='" . BASE_URL . "/public/index.php?accion=login';</script>";
        }
    }

    public function mostrarRegistro() {
        require BASE_PATH . '/views/register.php';
    }

    public function procesarRegistro() {
        header('Content-Type: application/json');

        $dni = $_POST['dni'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $usuario = new Usuario();
        $exito = $usuario->registrarUsuario($dni, $nombre, $apellido, $email, $password);

        if ($exito) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
        }
        exit;
    }
} 
