<?php
class HomeController {

    public function inicio() {
        require '../views/home.php';
    }

    public function mostrarSeccion($seccion) {
        $archivo = "../views/$seccion.php";
        if (file_exists($archivo)) {
            require $archivo;
        } else {
            echo "<h2 style='color: red;'>La sección '$seccion' no existe.</h2>";
        }
    }
}
