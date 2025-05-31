<?php
require_once BASE_PATH . '/config/Database.php';

class Clase {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function obtenerPorMentor($id_usuario) {
    // Obtener ID_DOCENTE
    $stmt = $this->pdo->prepare("SELECT ID_DOCENTE FROM docente WHERE ID_USUARIO = ?");
    $stmt->execute([$id_usuario]);
    $id_docente = $stmt->fetchColumn();

    if (!$id_docente) return [];

    // Obtener clases asociadas
    $stmt = $this->pdo->prepare("
        SELECT C.*, CU.NOMBRE AS curso
        FROM clase C
        JOIN curso CU ON C.ID_CURSO = CU.ID_CURSO
        WHERE C.ID_CLASE IN (
            SELECT ID_CLASE FROM registro_academico WHERE ID_DOCENTE = ?
        )
    ");
    $stmt->execute([$id_docente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


  public function insertarClase($data, $id_usuario) {
    // Obtener ID_DOCENTE desde el ID_USUARIO
    $stmt = $this->pdo->prepare("SELECT ID_DOCENTE FROM docente WHERE ID_USUARIO = ?");
    $stmt->execute([$id_usuario]);
    $id_docente = $stmt->fetchColumn();

    if (!$id_docente) {
        throw new Exception("Este usuario no está registrado como docente.");
    }

    // Insertar clase
    $stmt = $this->pdo->prepare("INSERT INTO clase 
        (HORARIO, ESTADO, FECHA_INICIO, FECHA_FIN, RAZON, CAPACIDAD, ID_AULA, ID_CURSO)
        VALUES (?, 1, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['HORARIO'],
        $data['FECHA_INICIO'],
        $data['FECHA_FIN'],
        $data['RAZON'],
        $data['CAPACIDAD'],
        $data['ID_AULA'],
        $data['ID_CURSO']
    ]);
    $id_clase = $this->pdo->lastInsertId();

    // Insert en registro_academico
    $stmt = $this->pdo->prepare("INSERT INTO registro_academico (ID_DOCENTE, ID_CLASE, FECHA_REG)
                                 VALUES (?, ?, NOW())");
    $stmt->execute([$id_docente, $id_clase]);
}


    public function obtenerPorId($id_clase) {
    $stmt = $this->pdo->prepare("SELECT * FROM clase WHERE ID_CLASE = ?");
    $stmt->execute([$id_clase]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function actualizarClase($id_clase, $data) {
    $stmt = $this->pdo->prepare("UPDATE clase SET 
        HORARIO = ?, FECHA_INICIO = ?, FECHA_FIN = ?, 
        RAZON = ?, CAPACIDAD = ?, ID_AULA = ?, ID_CURSO = ?
        WHERE ID_CLASE = ?");
    $stmt->execute([
        $data['HORARIO'],
        $data['FECHA_INICIO'],
        $data['FECHA_FIN'],
        $data['RAZON'],
        $data['CAPACIDAD'],
        $data['ID_AULA'],
        $data['ID_CURSO'],
        $id_clase
    ]);
}
public function eliminarClase($id_clase) {
    $stmt = $this->pdo->prepare("DELETE FROM clase WHERE ID_CLASE = ?");
    $stmt->execute([$id_clase]);
}
public function listarEstudiantesPorDocente($id_docente) {
    $stmt = $this->pdo->prepare("
        SELECT U.NOMBRE, U.APELLIDO, U.EMAIL, CU.NOMBRE AS CURSO, C.HORARIO
        FROM registro_academico RA
        JOIN estudiante E ON RA.ID_ESTUDIANTE = E.ID_ESTUDIANTE
        JOIN usuario U ON E.ID_USUARIO = U.ID_USUARIO
        JOIN clase C ON RA.ID_CLASE = C.ID_CLASE
        JOIN curso CU ON C.ID_CURSO = CU.ID_CURSO
        WHERE RA.ID_DOCENTE = ?
        ORDER BY U.APELLIDO
    ");
    $stmt->execute([$id_docente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function listarEstudiantesFiltrados($id_docente, $curso = '', $claseId = '') {
    $sql = "
        SELECT U.NOMBRE, U.APELLIDO, U.EMAIL, CU.NOMBRE AS CURSO, C.HORARIO
        FROM registro_academico RA
        JOIN estudiante E ON RA.ID_ESTUDIANTE = E.ID_ESTUDIANTE
        JOIN usuario U ON E.ID_USUARIO = U.ID_USUARIO
        JOIN clase C ON RA.ID_CLASE = C.ID_CLASE
        JOIN curso CU ON C.ID_CURSO = CU.ID_CURSO
        WHERE RA.ID_DOCENTE = ?
    ";

    $params = [$id_docente];

    if (!empty($curso)) {
        $sql .= " AND CU.NOMBRE = ?";
        $params[] = $curso;
    }

    if (!empty($claseId)) {
        $sql .= " AND C.ID_CLASE = ?";
        $params[] = $claseId;
    }

    $sql .= " ORDER BY U.APELLIDO";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function listarClasesPorDocente($id_docente) {
    $stmt = $this->pdo->prepare("
        SELECT DISTINCT C.ID_CLASE, CU.NOMBRE AS CURSO, C.HORARIO
        FROM registro_academico RA
        JOIN clase C ON RA.ID_CLASE = C.ID_CLASE
        JOIN curso CU ON C.ID_CURSO = CU.ID_CURSO
        WHERE RA.ID_DOCENTE = ?
        ORDER BY CU.NOMBRE
    ");
    $stmt->execute([$id_docente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerClasesPorEstudiante($id_estudiante) {
    $stmt = $this->pdo->prepare("
        SELECT CU.NOMBRE AS CURSO, C.HORARIO, C.FECHA_INICIO, C.FECHA_FIN,
               CONCAT(U.NOMBRE, ' ', U.APELLIDO) AS MENTOR
        FROM registro_academico RA
        JOIN clase C ON RA.ID_CLASE = C.ID_CLASE
        JOIN curso CU ON C.ID_CURSO = CU.ID_CURSO
        JOIN docente D ON RA.ID_DOCENTE = D.ID_DOCENTE
        JOIN usuario U ON D.ID_USUARIO = U.ID_USUARIO
        WHERE RA.ID_ESTUDIANTE = ?
    ");
    $stmt->execute([$id_estudiante]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function obtenerMentoresPorEstudiante($id_estudiante) {
    $stmt = $this->pdo->prepare("
        SELECT DISTINCT U.NOMBRE AS nombre, U.APELLIDO, U.EMAIL
        FROM registro_academico RA
        JOIN docente D ON RA.ID_DOCENTE = D.ID_DOCENTE
        JOIN usuario U ON D.ID_USUARIO = U.ID_USUARIO
        WHERE RA.ID_ESTUDIANTE = ?
    ");
    $stmt->execute([$id_estudiante]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
