<?php
session_start();
require_once '../config/constants.php';

$accion = $_GET['accion'] ?? 'inicio';

// Mapeo de acciones del AuthController
$authActions = [
    'login' => 'mostrarLogin',
    'procesar_login' => 'procesarLogin',
    'registro' => 'mostrarRegistro',
    'procesar_registro' => 'procesarRegistro',
    'cerrar' => 'cerrarSesion'
];

if (array_key_exists($accion, $authActions)) {
    require_once BASE_PATH . '/controllers/AuthController.php';
    $auth = new AuthController();

    if ($accion === 'cerrar') {
        session_destroy();
        header('Location: ' . BASE_URL . '/public/index.php');
        exit;
    }

    call_user_func([$auth, $authActions[$accion]]);
    exit;
}

switch ($accion) {
    case 'mentoria':
    case 'mentores':
    case 'alumnos':
    case 'anuncios':
    case 'faq':
    case 'testimonios':
        require_once BASE_PATH . '/controllers/HomeController.php';
        (new HomeController)->mostrarSeccion($accion);
        break;

    case 'inicio':
    default:
        require_once BASE_PATH . '/controllers/HomeController.php';
        (new HomeController)->inicio();
        break;
}