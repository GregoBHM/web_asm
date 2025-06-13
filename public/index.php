<?php
session_start();
require_once '../config/constants.php';
require_once BASE_PATH . '/core/BaseController.php';

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'inicio';
$usuarioId = $_SESSION['usuario_id'] ?? null;
$rolId = $_SESSION['rol_id'] ?? null;

if ($usuarioId) {
    include BASE_PATH . '/views/components/nav.php';
}

$authActions = ['login', 'procesar_login', 'registro', 'procesar_registro', 'cerrar'];
if (in_array($accion, $authActions)) {
    require_once BASE_PATH . '/controllers/AuthController.php';
    $auth = new AuthController();
    if ($accion === 'cerrar') {
        session_destroy();
        header('Location: ' . BASE_URL . '/public');
        exit;
    }
    $auth->handle($accion);
    exit;
}

$accionesPublicas = ['mentoria', 'mentores', 'alumnos', 'anuncios', 'faq', 'testimonios', 'inicio'];
if (in_array($accion, $accionesPublicas)) {
    require_once BASE_PATH . '/controllers/HomeController.php';
    $home = new HomeController();
    $home->handle($accion);
    exit;
}

if (!$usuarioId || !$rolId) {
    header('Location: ' . BASE_URL . '/public?accion=login');
    exit;
}

$mapaAcciones = [
    'crear_clase' => ['ClaseController', [3, 4]],
    'listar_clase' => ['ClaseController', [2, 3, 4]],
    'editar_clase' => ['ClaseController', [3, 4]],
    'eliminar_clase' => ['ClaseController', [4]],
    'solicitar_clase' => ['ClaseController', [2]],
    'dashboard' => ['UsuarioController', [1, 2, 3, 4]],
    'mis_clases' => ['EstudianteController', [2]],
    'clases_asignadas' => ['DocenteController', [3]],
    'gestion_usuarios' => ['AdminController', [4]]
];

if (isset($mapaAcciones[$accion])) {
    [$controladorNombre, $rolesPermitidos] = $mapaAcciones[$accion];
    if (in_array($rolId, $rolesPermitidos)) {
        require_once BASE_PATH . "/controllers/{$controladorNombre}.php";
        $ctrl = new $controladorNombre();
        $ctrl->handle($accion);
        exit;
    } else {
        http_response_code(403);
        echo "<h2>Acción no permitida para tu rol.</h2>";
        exit;
    }
}

$carpetasPorRol = [1 => 'usuario', 2 => 'estudiante', 3 => 'docente', 4 => 'admin'];
$carpeta = $carpetasPorRol[$rolId] ?? null;

if ($carpeta) {
    $ruta = BASE_PATH . "/views/$carpeta/$accion.php";
    $rutaComun = BASE_PATH . "/views/usuario/$accion.php";
    if (file_exists($ruta)) {
        include $ruta;
        exit;
    } elseif (file_exists($rutaComun)) {
        include $rutaComun;
        exit;
    }
}

http_response_code(404);
echo "<h2>Página no encontrada</h2>";
exit;