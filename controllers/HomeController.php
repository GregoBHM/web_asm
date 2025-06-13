<?php
class HomeController {

    public function inicio() {
        $archivo = BASE_PATH . '/views/home.php';
        if (file_exists($archivo)) {
            require $archivo;
        } else {
            echo "<h2 style='color: red;'>La página de inicio no existe.</h2>";
            echo "<p>Archivo esperado: <code>$archivo</code></p>";
        }
    }

    public function mostrarSeccion($seccion) {
        $seccionesPermitidas = ['mentoria', 'mentores', 'alumnos', 'anuncios', 'faq', 'testimonios'];
        
        if (!in_array($seccion, $seccionesPermitidas)) {
            echo "<h2 style='color: red;'>Sección no permitida.</h2>";
            return;
        }

        $archivo = BASE_PATH . "/views/$seccion.php";
        if (file_exists($archivo)) {
            require $archivo;
        } else {
            echo "<h2 style='color: red;'>La sección '$seccion' no existe.</h2>";
        }
    }
}