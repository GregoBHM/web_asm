<?php
session_start();
require_once '../config/constants.php';

$accion = $_GET['accion'] ?? 'inicio';

// Debug temporal - eliminar después de verificar
// echo "Sesión: "; var_dump($_SESSION);
// echo "Acción: " . $accion;

if (isset($_SESSION['usuario_id'])) {
    include BASE_PATH . '/views/components/nav.php';
}

// Mapeo de acciones del AuthController
$authActions = [
    'login' => 'mostrarLogin',
    'procesar_login' => 'procesarLogin',
    'registro' => 'mostrarRegistro',
    'procesar_registro' => 'procesarRegistro',
    'cerrar' => 'cerrarSesion'
];

// Procesar acciones de autenticación
if (array_key_exists($accion, $authActions)) {
    require_once BASE_PATH . '/controllers/AuthController.php';
    $auth = new AuthController();

    if ($accion === 'cerrar') {
        session_destroy();
        header('Location: ' . BASE_URL . '/public');
        exit;
    }

    call_user_func([$auth, $authActions[$accion]]);
    exit;
}
$accionesPublicas = ['mentoria', 'mentores', 'alumnos', 'anuncios', 'faq', 'testimonios', 'inicio'];

if (in_array($accion, $accionesPublicas)) {
    require_once BASE_PATH . '/controllers/HomeController.php';
    $homeController = new HomeController();
    
    if ($accion === 'inicio') {
        $homeController->inicio();
    } else {
        $homeController->mostrarSeccion($accion);
    }
    exit;
}

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol_id'])) {
    header('Location: ' . BASE_URL . '/public?accion=login');
    exit;
}

$carpetasPorRol = [
    1 => 'usuario',
    2 => 'estudiante', 
    3 => 'docente',
    4 => 'admin'
];

if (isset($carpetasPorRol[$_SESSION['rol_id']])) {
    $carpeta = $carpetasPorRol[$_SESSION['rol_id']];
    $rutaVista = BASE_PATH . "/views/$carpeta/$accion.php";
    $rutaVistaComun = BASE_PATH . "/views/usuario/$accion.php";

    if (file_exists($rutaVista)) {
        include $rutaVista;
        exit;
    }
    elseif (file_exists($rutaVistaComun)) {
        include $rutaVistaComun;
        exit;
    }
    else {
        http_response_code(404);
        echo "<h2>Página no encontrada</h2>";
        echo "<p>La vista <code>$accion.php</code> no existe en <code>$carpeta/</code> ni en <code>usuario/</code>.</p>";
        echo "<p><a href='" . BASE_URL . "/public'>Volver al inicio</a></p>";
        exit;
    }
} else {
    http_response_code(403);
    echo "<h2>Rol no autorizado</h2>";
    echo "<p>El rol de usuario no es válido.</p>";
    exit;
}