<?php
require_once 'models/ClaseModel.php';

class ClaseController {
    private $claseModel;
    
    public function __construct($database) {
        $this->claseModel = new ClaseModel($database);
    }
    
    // Mostrar lista de clases del estudiante
    public function mostrarClasesEstudiante() {
        session_start();
        
        // Verificar que el usuario esté logueado y sea estudiante
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['id_estudiante'])) {
            header('Location: login.php');
            exit();
        }
        
        $idEstudiante = $_SESSION['id_estudiante'];
        $clases = $this->claseModel->obtenerClasesPorEstudiante($idEstudiante);
        
        include 'views/clases/mis_clases.php';
    }
    
    // Mostrar detalles de una clase específica
    public function verDetalleClase() {
        session_start();
        
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header('Location: mis_clases.php');
            exit();
        }
        
        $idClase = $_GET['id'];
        $detalleClase = $this->claseModel->obtenerDetalleClase($idClase);
        
        if (!$detalleClase) {
            $_SESSION['error'] = "Clase no encontrada";
            header('Location: mis_clases.php');
            exit();
        }
        
        include 'views/clases/detalle_clase.php';
    }
    
    // Mostrar formulario de calificación del mentor
    public function mostrarCalificarMentor() {
        session_start();
        
        if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !isset($_SESSION['id_estudiante'])) {
            header('Location: mis_clases.php');
            exit();
        }
        
        $idClase = $_GET['id'];
        $idEstudiante = $_SESSION['id_estudiante'];
        
        // Verificar si ya calificó
        if ($this->claseModel->verificarCalificacionExistente($idEstudiante, $idClase)) {
            $_SESSION['error'] = "Ya has calificado al mentor de esta clase";
            header('Location: mis_clases.php');
            exit();
        }
        
        $detalleClase = $this->claseModel->obtenerDetalleClase($idClase);
        
        if (!$detalleClase) {
            $_SESSION['error'] = "Clase no encontrada";
            header('Location: mis_clases.php');
            exit();
        }
        
        include 'views/clases/calificar_mentor.php';
    }
    
    // Procesar calificación del mentor
    public function procesarCalificacionMentor() {
        session_start();
        
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['id_estudiante'])) {
            header('Location: login.php');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idClase = $_POST['id_clase'];
            $puntuacion = $_POST['puntuacion'];
            $comentario = $_POST['comentario'];
            $idEstudiante = $_SESSION['id_estudiante'];
            
            // Validaciones
            if (empty($puntuacion) || $puntuacion < 1 || $puntuacion > 5) {
                $_SESSION['error'] = "Debe seleccionar una puntuación válida (1-5)";
                header('Location: calificar_mentor.php?id=' . $idClase);
                exit();
            }
            
            if (empty($comentario)) {
                $_SESSION['error'] = "El comentario es obligatorio";
                header('Location: calificar_mentor.php?id=' . $idClase);
                exit();
            }
            
            // Guardar calificación
            if ($this->claseModel->guardarCalificacionMentor($idEstudiante, $idClase, $puntuacion, $comentario)) {
                $_SESSION['success'] = "Calificación enviada exitosamente";
            } else {
                $_SESSION['error'] = "Error al enviar la calificación";
            }
            
            header('Location: mis_clases.php');
            exit();
        }
    }
}

// Manejo de rutas
if (isset($_GET['action'])) {
    $database = new PDO("mysql:host=localhost;dbname=sistema_mentoria", "root", "");
    $controller = new ClaseController($database);
    
    switch($_GET['action']) {
        case 'ver_detalle':
            $controller->verDetalleClase();
            break;
        case 'calificar_mentor':
            $controller->mostrarCalificarMentor();
            break;
        case 'procesar_calificacion':
            $controller->procesarCalificacionMentor();
            break;
        default:
            $controller->mostrarClasesEstudiante();
            break;
    }
} else {
    $database = new PDO("mysql:host=localhost;dbname=sistema_mentoria", "root", "");
    $controller = new ClaseController($database);
    $controller->mostrarClasesEstudiante();
}
?>