<?php
require_once 'models/ClaseModel.php';
require_once 'core/BaseController.php';

class ClaseController extends BaseController {
    private $claseModel;

    public function __construct() {
        $this->claseModel = new ClaseModel();
    }

    public function handle($accion) {
        switch ($accion) {
            case 'listar_clase':
                $this->listar();
                break;
            case 'solicitar_clase':
                $this->solicitarClase();
                break;
            case 'obtener_cursos_ajax':
                $this->obtenerCursosAjax();
                break;
            default:
                http_response_code(404);
                echo "<h2>Acción no encontrada</h2>";
        }
    }

    public function listar() {
        session_start();

        $clasesDisponibles = $this->claseModel->listarClasesDisponibles();
        $ciclos = $this->claseModel->listarCiclos();

        require BASE_PATH . '/views/estudiante/clases_disponibles.php';
    }

    public function solicitarClase() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idEstudiante = $_SESSION['id_estudiante'] ?? null;

            if (!$idEstudiante) {
                $_SESSION['error'] = "Debes iniciar sesión como estudiante.";
                header('Location: ?accion=listar_clase');
                exit;
            }

            $resultado = $this->claseModel->solicitarNuevaClase(
                $idEstudiante,
                $_POST['ciclo'] ?? null,
                $_POST['curso'] ?? null,
                $_POST['horario_preferido'] ?? '',
                $_POST['observaciones'] ?? ''
            );

            if ($resultado) {
                $_SESSION['success'] = "Solicitud registrada correctamente.";
            } else {
                $_SESSION['error'] = "Ocurrió un error al registrar la solicitud.";
            }

            header('Location: ?accion=listar_clase');
            exit;
        }
    }

    public function obtenerCursosAjax() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ciclo_id'])) {
            $cicloId = $_POST['ciclo_id'];
            $cursos = $this->claseModel->obtenerCiclosDisponibles();
            header('Content-Type: application/json');
            echo json_encode($cursos);
            exit;
        }

        http_response_code(400);
        echo json_encode(["error" => "Solicitud inválida"]);
        exit;
    }
}
