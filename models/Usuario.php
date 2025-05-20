<?php
require_once '../config/Database.php';

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function verificarCredenciales($email, $password) {
        $stmt = $this->pdo->prepare("SELECT U.ID_USUARIO, U.PASSWORD, R.NOMBRE AS ROL
                                     FROM USUARIO U
                                     INNER JOIN ROLES_ASIGNADOS RA ON U.ID_USUARIO = RA.ID_USUARIO
                                     INNER JOIN ROL R ON RA.ID_ROL = R.ID_ROL
                                     WHERE U.EMAIL = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['PASSWORD'])) {
            return $user;
        }
        return false;
    }

    public function registrarUsuario($nombre, $apellido, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO USUARIO (NOMBRE, APELLIDO, EMAIL, PASSWORD) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $email, $hash]);

        $user_id = $this->pdo->lastInsertId();

        // Rol visitante por defecto (ID_ROL = 1)
        $stmt = $this->pdo->prepare("INSERT INTO ROLES_ASIGNADOS (ID_USUARIO, ID_ROL, FECHA_REG, ESTADO) VALUES (?, 1, NOW(), 1)");
        $stmt->execute([$user_id]);
    }
    public function buscarPorCorreo($email) {
        $stmt = $this->pdo->prepare("SELECT U.ID_USUARIO, R.NOMBRE AS ROL
            FROM USUARIO U
            JOIN ROLES_ASIGNADOS RA ON RA.ID_USUARIO = U.ID_USUARIO
            JOIN ROL R ON RA.ID_ROL = R.ID_ROL
            WHERE U.EMAIL = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function registrarOAuth($nombre, $apellido, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO USUARIO (NOMBRE, APELLIDO, EMAIL) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $email]);

        $user_id = $this->pdo->lastInsertId();

        // Rol visitante (ID_ROL = 1)
        $stmt = $this->pdo->prepare("INSERT INTO ROLES_ASIGNADOS (ID_USUARIO, ID_ROL, FECHA_REG, ESTADO) VALUES (?, 1, NOW(), 1)");
        $stmt->execute([$user_id]);

        return $user_id;
    }
}
