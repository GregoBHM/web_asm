<?php
$input = '123456';
$hash = '$2y$10$g6eqkmxfhzSnX8tk2DLZLOFHTHHgXEQpnRtOH6M.RvOskVlmWgHnK';

if (password_verify($input, $hash)) {
    echo "✔️ Contraseña correcta";
} else {
    echo "❌ Contraseña incorrecta";
}
