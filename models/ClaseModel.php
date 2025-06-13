<?php
class ClaseModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function obtenerClasesPorEstudiante($idEstudiante) {
        $query = "SELECT 
                    c.ID_CLASE,
                    c.HORARIO,
                    c.ESTADO,
                    c.FECHA_INICIO,
                    c.FECHA_FIN,
                    c.CAPACIDAD,
                    cur.NOMBRE as NOMBRE_CURSO,
                    cur.CODIGO as CODIGO_CURSO,
                    a.NOMBRE as NOMBRE_AULA,
                    ci.NOMBRE as NOMBRE_CICLO,
                    sa.CODIGO as CODIGO_SEMESTRE,
                    i.FECHA_REG as FECHA_INSCRIPCION
                FROM inscripcion i
                INNER JOIN clase c ON i.ID_CLASE = c.ID_CLASE
                INNER JOIN curso cur ON c.ID_CURSO = cur.ID_CURSO
                INNER JOIN aula a ON c.ID_AULA = a.ID_AULA
                INNER JOIN ciclo ci ON cur.ID_CICLO = ci.ID_CICLO
                INNER JOIN semestre_academico sa ON ci.ID_SEMESTRE = sa.ID_SEMESTRE
                WHERE i.ID_ESTUDIANTE = :idEst
                ORDER BY c.FECHA_INICIO DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idEst', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerDetalleClase($idClase) {
        $query = "SELECT 
                    c.*,
                    cur.NOMBRE as NOMBRE_CURSO,
                    cur.CODIGO as CODIGO_CURSO,
                    a.NOMBRE as NOMBRE_AULA,
                    ci.NOMBRE as NOMBRE_CICLO,
                    sa.CODIGO as CODIGO_SEMESTRE,
                    u.NOMBRE as NOMBRE_DOCENTE,
                    u.APELLIDO as APELLIDO_DOCENTE,
                    u.EMAIL as EMAIL_DOCENTE
                FROM clase c
                INNER JOIN curso cur ON c.ID_CURSO = cur.ID_CURSO
                INNER JOIN aula a ON c.ID_AULA = a.ID_AULA
                INNER JOIN ciclo ci ON cur.ID_CICLO = ci.ID_CICLO
                INNER JOIN semestre_academico sa ON ci.ID_SEMESTRE = sa.ID_SEMESTRE
                LEFT JOIN registro_academico ra ON c.ID_CLASE = ra.ID_CLASE
                LEFT JOIN docente d ON ra.ID_DOCENTE = d.ID_DOCENTE
                LEFT JOIN usuario u ON d.ID_USUARIO = u.ID_USUARIO
                WHERE c.ID_CLASE = :idClase
                LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idClase', $idClase, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  
    public function verificarCalificacionExistente($idEstudiante, $idClase) {
        $query = "SELECT COUNT(*) as total 
                FROM comentario co
                INNER JOIN registro_academico ra ON co.ID_DOCENTE = ra.ID_DOCENTE
                WHERE co.ID_ESTUDIANTE = :idEst AND ra.ID_CLASE = :idClase";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idEst', $idEstudiante, PDO::PARAM_INT);
        $stmt->bindParam(':idClase', $idClase, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    public function guardarCalificacionMentor($idEstudiante, $idClase, $puntuacion, $comentario) {
        try {
            $this->db->beginTransaction();
            
            $queryDocente = "SELECT ra.ID_DOCENTE 
                           FROM registro_academico ra 
                           WHERE ra.ID_CLASE = :idClase 
                           LIMIT 1";
            
            $stmtDocente = $this->db->prepare($queryDocente);
            $stmtDocente->bindParam(':idClase', $idClase, PDO::PARAM_INT);
            $stmtDocente->execute();
            $docente = $stmtDocente->fetch(PDO::FETCH_ASSOC);
            
            if (!$docente) {
                throw new Exception("No se encontró docente para esta clase");
            }
            
            $queryComentario = "INSERT INTO comentario (PUNTUACION, ID_DOCENTE, ID_ESTUDIANTE, COMENTARIO_TEXTO) 
                              VALUES (:puntuacion, :idDocente, :idEstudiante, :comentario)";
            
            $stmtComentario = $this->db->prepare($queryComentario);
            $stmtComentario->bindParam(':puntuacion', $puntuacion, PDO::PARAM_INT);
            $stmtComentario->bindParam(':idDocente', $docente['ID_DOCENTE'], PDO::PARAM_INT);
            $stmtComentario->bindParam(':idEstudiante', $idEstudiante, PDO::PARAM_INT);
            $stmtComentario->bindParam(':comentario', $comentario, PDO::PARAM_STR);
            
            $stmtComentario->execute();
            
            $this->db->commit();
            return true;
                
        } catch(Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}
?>