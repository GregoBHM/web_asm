function confirmarCerrarSesion(e, logoutUrl) {
    e.preventDefault();

    if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
        const overlay = document.createElement("div");
        overlay.style.position = "fixed";
        overlay.style.top = 0;
        overlay.style.left = 0;
        overlay.style.width = "100vw";
        overlay.style.height = "100vh";
        overlay.style.backgroundColor = "rgba(0,0,0,0.6)";
        overlay.style.display = "flex";
        overlay.style.justifyContent = "center";
        overlay.style.alignItems = "center";
        overlay.style.zIndex = 9999;

        const spinner = document.createElement("div");
        spinner.innerHTML = `
            <div style="color: white; text-align: center;">
                <div class="loader"></div>
                <p style="margin-top: 10px;">Cerrando sesión...</p>
            </div>
        `;

        overlay.appendChild(spinner);
        document.body.appendChild(overlay);

        setTimeout(() => {
            window.location.href = logoutUrl;
        }, 2000);
    }
}

//register

let codigoEnviado = "";

function validarDNI(dni) {
    if (/^\d{8}$/.test(dni)) {
        mostrarCarga(true);
        fetch(`https://api.apis.net.pe/v1/dni?numero=${dni}`, {
            headers: { Authorization: 'Bearer TU_TOKEN_RENIEC' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.nombres) {
                document.getElementById("nombre").value = data.nombres;
                document.getElementById("apellido").value = `${data.apellidoPaterno} ${data.apellidoMaterno}`;
                document.getElementById("email").focus();
            } else {
                limpiarDNI("DNI inválido. Intenta nuevamente.");
            }
        })
        .catch(() => limpiarDNI("Error en la verificación del DNI."))
        .finally(() => mostrarCarga(false));
    } else {
        document.getElementById("nombre").value = "";
        document.getElementById("apellido").value = "";
    }
}

function limpiarDNI(msg) {
    alert(msg);
    document.getElementById("dni").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("apellido").value = "";
    document.getElementById("dni").focus();
}

function validarCorreo(input) {
    const correo = input.value;
    const regex = /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    document.getElementById("correoError").textContent = regex.test(correo) ? "" : "Correo no válido";
}

function validarPassword(pass) {
    const fuerte = /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^&\*])/;
    document.getElementById("passError").textContent = fuerte.test(pass)
        ? ""
        : "Contraseña insegura. Usa mayúsculas, números y símbolos.";
}

function compararPassword() {
    const pass = document.getElementById("password").value;
    const confirm = document.getElementById("confirmar").value;
    const boton = document.getElementById("btnRegistrar");
    document.getElementById("passError").textContent = pass !== confirm ? "Las contraseñas no coinciden." : "";
    boton.disabled = !(pass && confirm && pass === confirm);
}

document.getElementById("formRegistro").addEventListener("submit", function(e) {
    e.preventDefault();
    const correo = document.getElementById("email").value;

    mostrarCarga(true);
    fetch("<?= BASE_URL ?>/auth/enviar_codigo.php", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ correo: correo })
    })
    .then(res => res.json())
    .then(data => {
        if (data.codigo) {
            codigoEnviado = data.codigo;
            document.getElementById("modalCodigo").style.display = "block";
        }
    })
    .finally(() => mostrarCarga(false));
});

function verificarCodigo() {
    const input = document.getElementById("codigoVerificacion").value;
    if (input === codigoEnviado) {
        document.getElementById("formRegistro").submit();
    } else {
        alert("Código incorrecto.");
    }
}

function mostrarCarga(estado) {
    if (estado) {
        const carga = document.createElement("div");
        carga.id = "cargandoOverlay";
        carga.style = "position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center;z-index:9999;";
        carga.innerHTML = '<div style="color:white;">Validando...<div class="loader"></div></div>';
        document.body.appendChild(carga);
    } else {
        const carga = document.getElementById("cargandoOverlay");
        if (carga) carga.remove();
    }
}
