<?php 
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-list-alt"></i> Clases Disponibles
                    </h4>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalSolicitarClase">
                        <i class="fas fa-plus"></i> Solicitar Clase
                    </button>
                </div>
                <div class="card-body">
                    
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="filtroCiclo" class="form-label">Filtrar por Ciclo:</label>
                            <select class="form-select" id="filtroCiclo">
                                <option value="">Todos los ciclos</option>
                                <!-- Opciones se llenarán dinámicamente -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroCurso" class="form-label">Filtrar por Curso:</label>
                            <select class="form-select" id="filtroCurso">
                                <option value="">Todos los cursos</option>
                                <!-- Opciones se llenarán dinámicamente -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroDisponibilidad" class="form-label">Disponibilidad:</label>
                            <select class="form-select" id="filtroDisponibilidad">
                                <option value="">Todas</option>
                                <option value="disponible">Disponibles</option>
                                <option value="lleno">Llenas</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-primary" onclick="aplicarFiltros()">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>

                    <!-- Mensajes de estado -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <!-- Tabla de clases disponibles -->
                    <?php if (empty($clasesDisponibles)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay clases disponibles</h5>
                            <p class="text-muted">Solicita una nueva clase o revisa más tarde.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="tablaClases">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Código</th>
                                        <th>Curso</th>
                                        <th>Ciclo</th>
                                        <th>Aula</th>
                                        <th>Horario</th>
                                        <th>Fecha Inicio</th>
                                        <th>Disponibilidad</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clasesDisponibles as $clase): ?>
                                        <?php 
                                        $inscritos = $clase['INSCRITOS'] ?? 0;
                                        $capacidad = $clase['CAPACIDAD'] ?? 0;
                                        $disponible = $capacidad > $inscritos;
                                        $porcentaje = $capacidad > 0 ? ($inscritos / $capacidad) * 100 : 0;
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($clase['CODIGO_CURSO']); ?></span>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($clase['NOMBRE_CURSO']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($clase['NOMBRE_CICLO']); ?></td>
                                            <td>
                                                <i class="fas fa-door-open"></i> 
                                                <?php echo htmlspecialchars($clase['NOMBRE_AULA']); ?>
                                            </td>
                                            <td>
                                                <i class="fas fa-clock"></i> 
                                                <?php echo htmlspecialchars($clase['HORARIO']); ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($clase['FECHA_INICIO']) {
                                                    echo date('d/m/Y', strtotime($clase['FECHA_INICIO']));
                                                } else {
                                                    echo '<span class="text-muted">Por definir</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 100px; height: 20px;">
                                                        <div class="progress-bar <?php echo $disponible ? 'bg-success' : 'bg-danger'; ?>" 
                                                             role="progressbar" 
                                                             style="width: <?php echo $porcentaje; ?>%">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?php echo $inscritos; ?>/<?php echo $capacidad; ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($disponible): ?>
                                                    <button type="button" 
                                                            class="btn btn-success btn-sm" 
                                                            onclick="confirmarInscripcion(<?php echo $clase['ID_CLASE']; ?>)"
                                                            title="Unirse a la clase">
                                                        <i class="fas fa-user-plus"></i> Unirse
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm" 
                                                            disabled
                                                            title="Clase llena">
                                                        <i class="fas fa-users"></i> Llena
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmación de Inscripción -->
<div class="modal fade" id="modalConfirmarInscripcion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus"></i> Confirmar Inscripción
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Confirma que deseas inscribirte en esta clase. Una vez inscrito, podrás ver la clase en tu panel de "Mis Clases".
                </div>
                
                <div id="detallesClaseModal">
                    <!-- Los detalles se cargarán dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="btnConfirmarInscripcion">
                    <i class="fas fa-check"></i> Confirmar Inscripción
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Solicitar Clase -->
<div class="modal fade" id="modalSolicitarClase" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Solicitar Nueva Clase
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSolicitarClase" method="POST" action="?action=solicitar_clase">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Tu solicitud será revisada por el equipo académico. Recibirás una notificación cuando sea aprobada.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cicloSolicitud" class="form-label">
                                    <i class="fas fa-layer-group"></i> Ciclo *
                                </label>
                                <select class="form-select" id="cicloSolicitud" name="ciclo" required>
                                    <option value="">Selecciona un ciclo</option>
                                    <?php foreach ($ciclos as $ciclo): ?>
                                        <option value="<?php echo $ciclo['ID_CICLO']; ?>">
                                            <?php echo htmlspecialchars($ciclo['NOMBRE']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cursoSolicitud" class="form-label">
                                    <i class="fas fa-book"></i> Curso *
                                </label>
                                <select class="form-select" id="cursoSolicitud" name="curso" required>
                                    <option value="">Primero selecciona un ciclo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="horarioPreferido" class="form-label">
                                    <i class="fas fa-clock"></i> Horario Preferido *
                                </label>
                                <select class="form-select" id="horarioPreferido" name="horario_preferido" required>
                                    <option value="">Selecciona un horario</option>
                                    <option value="08:00 - 10:00">08:00 - 10:00</option>
                                    <option value="10:00 - 12:00">10:00 - 12:00</option>
                                    <option value="14:00 - 16:00">14:00 - 16:00</option>
                                    <option value="16:00 - 18:00">16:00 - 18:00</option>
                                    <option value="18:00 - 20:00">18:00 - 20:00</option>
                                    <option value="20:00 - 22:00">20:00 - 22:00</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modalidadPreferida" class="form-label">
                                    <i class="fas fa-laptop"></i> Modalidad Preferida
                                </label>
                                <select class="form-select" id="modalidadPreferida" name="modalidad">
                                    <option value="presencial">Presencial</option>
                                    <option value="virtual">Virtual</option>
                                    <option value="hibrida">Híbrida</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="justificacion" class="form-label">
                            <i class="fas fa-comment"></i> Justificación de la Solicitud *
                        </label>
                        <textarea class="form-control" 
                                  id="justificacion" 
                                  name="justificacion" 
                                  rows="3" 
                                  placeholder="Explica brevemente por qué necesitas esta clase..."
                                  required></textarea>
                        <div class="form-text">Mínimo 20 caracteres</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">
                            <i class="fas fa-sticky-note"></i> Observaciones Adicionales
                        </label>
                        <textarea class="form-control" 
                                  id="observaciones" 
                                  name="observaciones" 
                                  rows="2" 
                                  placeholder="Información adicional que consideres relevante..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variables globales
let claseSeleccionada = null;
const clasesData = <?php echo json_encode($clasesDisponibles); ?>;

// Función para confirmar inscripción
function confirmarInscripcion(idClase) {
    claseSeleccionada = idClase;
    const clase = clasesData.find(c => c.ID_CLASE == idClase);
    
    if (clase) {
        const detallesHTML = `
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Detalles de la Clase</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Curso:</strong> ${clase.NOMBRE_CURSO}</p>
                            <p><strong>Código:</strong> <span class="badge bg-secondary">${clase.CODIGO_CURSO}</span></p>
                            <p><strong>Ciclo:</strong> ${clase.NOMBRE_CICLO}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Aula:</strong> ${clase.NOMBRE_AULA}</p>
                            <p><strong>Horario:</strong> ${clase.HORARIO}</p>
                            <p><strong>Disponibilidad:</strong> ${clase.INSCRITOS || 0}/${clase.CAPACIDAD} estudiantes</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('detallesClaseModal').innerHTML = detallesHTML;
        document.getElementById('btnConfirmarInscripcion').onclick = function() {
            inscribirseClase(idClase);
        };
        
        new bootstrap.Modal(document.getElementById('modalConfirmarInscripcion')).show();
    }
}

// Función para procesar inscripción
function inscribirseClase(idClase) {
    // Aquí llamarías a tu método del controlador
    window.location.href = `?action=inscribirse&id_clase=${idClase}`;
}

// Cargar cursos según ciclo seleccionado
document.getElementById('cicloSolicitud').addEventListener('change', function() {
    const cicloId = this.value;
    const cursoSelect = document.getElementById('cursoSolicitud');
    
    if (cicloId) {
        // Aquí harías una llamada AJAX para cargar los cursos del ciclo
        // Por ahora, un ejemplo básico
        cursoSelect.innerHTML = '<option value="">Cargando cursos...</option>';
        
        // Simular llamada AJAX
        setTimeout(() => {
            // Aquí pondrías los cursos reales desde tu controlador
            cursoSelect.innerHTML = `
                <option value="">Selecciona un curso</option>
                <option value="1">Matemática I</option>
                <option value="2">Física I</option>
                <option value="3">Química General</option>
            `;
        }, 500);
    } else {
        cursoSelect.innerHTML = '<option value="">Primero selecciona un ciclo</option>';
    }
});

// Validación del formulario de solicitud
document.getElementById('formSolicitarClase').addEventListener('submit', function(e) {
    const justificacion = document.getElementById('justificacion').value;
    
    if (justificacion.length < 20) {
        e.preventDefault();
        alert('La justificación debe tener al menos 20 caracteres.');
        return false;
    }
});

// Aplicar filtros a la tabla
function aplicarFiltros() {
    const filtroCiclo = document.getElementById('filtroCiclo').value.toLowerCase();
    const filtroCurso = document.getElementById('filtroCurso').value.toLowerCase();
    const filtroDisponibilidad = document.getElementById('filtroDisponibilidad').value;
    
    const tabla = document.getElementById('tablaClases');
    const filas = tabla.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < filas.length; i++) {
        const fila = filas[i];
        const ciclo = fila.cells[2].textContent.toLowerCase();
        const curso = fila.cells[1].textContent.toLowerCase();
        const disponibilidad = fila.cells[7].querySelector('button').classList.contains('btn-success') ? 'disponible' : 'lleno';
        
        let mostrar = true;
        
        if (filtroCiclo && !ciclo.includes(filtroCiclo)) {
            mostrar = false;
        }
        
        if (filtroCurso && !curso.includes(filtroCurso)) {
            mostrar = false;
        }
        
        if (filtroDisponibilidad && disponibilidad !== filtroDisponibilidad) {
            mostrar = false;
        }
        
        fila.style.display = mostrar ? '' : 'none';
    }
}
</script>

<style>
.progress {
    background-color: #e9ecef;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {    
    font-size: 0.75em;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
}

.modal-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.modal-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.125);
}

.form-text {
    font-size: 0.875em;
    color: #6c757d;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.btn-group .btn {
    margin-right: 2px;
}
</style>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>