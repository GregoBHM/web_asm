<?php
require_once BASE_PATH . '/models/Usuario.php';
require_once BASE_PATH . '/models/ClaseModel.php';

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol_id'], [2])) {
    header('Location: ' . BASE_URL . '/public/index.php?accion=login');
    exit;
}

$usuarioModel = new Usuario();
$claseModel = new ClaseModel();

$id_estudiante = $usuarioModel->obtenerIdEstudiante($_SESSION['usuario_id']);

if (!$id_estudiante) {
    $error = "No se encontró información de estudiante para este usuario.";
    $clases = [];
} else {
    $clases = $claseModel->obtenerClasesEstudiante($id_estudiante);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'calificar') {
    $id_clase = $_POST['id_clase'] ?? 0;
    $puntuacion = $_POST['puntuacion'] ?? 0;
    $comentario = $_POST['comentario'] ?? '';
    
    try {
        if ($puntuacion < 1 || $puntuacion > 5) {
            throw new Exception("La puntuación debe estar entre 1 y 5");
        }
        
        $resultado = $claseModel->calificarMentor($id_estudiante, $id_clase, $puntuacion, $comentario);
        
        if ($resultado) {
            $mensaje = "Calificación enviada correctamente";
            $tipo_mensaje = "success";
            $clases = $claseModel->obtenerClasesEstudiante($id_estudiante);
        } else {
            throw new Exception("No se pudo enviar la calificación");
        }
        
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}

require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<style>
:root {
    --accent-blue: #5a73c4;
    --light-blue: #e8f0fe;
    --dark-blue: #2d4482;
    --success-green: #28a745;
    --warning-orange: #ffc107;
    --danger-red: #dc3545;
    --light-gray: #f8f9fa;
    --border-gray: #e9ecef;
    --text-gray: #495057;
}

.clases-container {
    background-color: #f8f9fa;
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

.clase-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(60, 90, 166, 0.1);
    transition: all 0.3s ease;
    border-radius: 0.75rem;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.clase-card:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(60, 90, 166, 0.15);
    transform: translateY(-3px);
}

.clase-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
}

.clase-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    transform: rotate(45deg);
}

.clase-titulo {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 600;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 1;
}

.clase-codigo {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.9;
    position: relative;
    z-index: 1;
}

.estado-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 2;
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.estado-activo {
    background-color: var(--success-green);
    color: white;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
}

.estado-finalizado {
    background-color: var(--accent-blue);
    color: white;
    box-shadow: 0 2px 4px rgba(90, 115, 196, 0.3);
}

.estado-cancelado {
    background-color: var(--danger-red);
    color: white;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

.clase-body {
    padding: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.info-item {
    background-color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    border-left: 4px solid var(--primary-blue);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.info-label {
    font-weight: 600;
    color: var(--text-gray);
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    color: var(--dark-blue);
    font-size: 1rem;
    font-weight: 500;
}

.acciones-container {
    border-top: 1px solid var(--border-gray);
    padding-top: 1.5rem;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-calificar {
    background: linear-gradient(135deg, var(--warning-orange) 0%, #e0a800 100%);
    border: none;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-calificar:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
    color: white;
}

.btn-calificar:disabled {
    background: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-ver-notas {
    background: linear-gradient(135deg, var(--accent-blue) 0%, var(--primary-blue) 100%);
    border: none;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-ver-notas:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(90, 115, 196, 0.4);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(60, 90, 166, 0.1);
}

.empty-icon {
    font-size: 4rem;
    color: var(--accent-blue);
    margin-bottom: 1rem;
}

.page-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 1rem 1rem;
}

.rating-stars {
    display: inline-flex;
    gap: 0.25rem;
}

.star {
    font-size: 1.2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s ease;
}

.star.active,
.star:hover {
    color: var(--warning-orange);
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    border-bottom: none;
}

.modal-content {
    border: none;
    border-radius: 0.75rem;
    overflow: hidden;
}

.ya-calificado {
    background-color: var(--light-blue);
    color: var(--dark-blue);
    padding: 0.75rem;
    border-radius: 0.5rem;
    text-align: center;
    font-weight: 500;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.clase-card {
    animation: fadeInUp 0.5s ease-out;
}

.clase-card:nth-child(1) { animation-delay: 0.1s; }
.clase-card:nth-child(2) { animation-delay: 0.2s; }
.clase-card:nth-child(3) { animation-delay: 0.3s; }
.clase-card:nth-child(4) { animation-delay: 0.4s; }

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .acciones-container {
        flex-direction: column;
    }
    
    .clase-body {
        padding: 1rem;
    }
    
    .estado-badge {
        position: static;
        margin-top: 0.5rem;
        display: inline-block;
    }
    
    .clase-header {
        padding: 1rem;
    }
}
</style>

<div class="clases-container">
    <div class="container">
        <div class="page-header">
            <div class="container">
                <h1 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-3"></i>
                    Mis Clases
                </h1>
                <p class="mb-0 mt-2 opacity-75">Gestiona tus clases inscritas y califica a tus mentores</p>
            </div>
        </div>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?= $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($clases)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="text-muted">No tienes clases inscritas</h3>
                <p class="text-muted mb-4">
                    Aún no te has inscrito en ninguna clase. ¡Explora las clases disponibles y comienza tu aprendizaje!
                </p>
                <a href="<?= BASE_URL ?>/public/index.php?accion=clases_disponibles" class="btn btn-primary btn-lg">
                    <i class="fas fa-search me-2"></i>
                    Buscar Clases
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($clases as $clase): ?>
                    <div class="col-lg-6 col-xl-4">
                        <div class="clase-card">
                            <div class="clase-header">
                                <div class="estado-badge estado-<?= strtolower($clase['ESTADO_TEXTO']) ?>">
                                    <?= htmlspecialchars($clase['ESTADO_TEXTO']) ?>
                                </div>
                                <h4 class="clase-titulo">
                                    <?= htmlspecialchars($clase['CURSO_NOMBRE']) ?>
                                </h4>
                                <p class="clase-codigo">
                                    Código: <?= htmlspecialchars($clase['CURSO_CODIGO']) ?>
                                </p>
                            </div>
                            
                            <div class="clase-body">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-clock me-1"></i>
                                            Horario
                                        </div>
                                        <div class="info-value">
                                            <?= htmlspecialchars($clase['HORARIO']) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Inicio
                                        </div>
                                        <div class="info-value">
                                            <?= date('d/m/Y', strtotime($clase['FECHA_INICIO'])) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Fin
                                        </div>
                                        <div class="info-value">
                                            <?= date('d/m/Y', strtotime($clase['FECHA_FIN'])) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-door-open me-1"></i>
                                            Aula
                                        </div>
                                        <div class="info-value">
                                            <?= htmlspecialchars($clase['AULA_NOMBRE'] ?? 'Virtual') ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-layer-group me-1"></i>
                                            Ciclo
                                        </div>
                                        <div class="info-value">
                                            <?= htmlspecialchars($clase['CICLO_NOMBRE']) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-users me-1"></i>
                                            Capacidad
                                        </div>
                                        <div class="info-value">
                                            <?= $clase['INSCRITOS'] ?>/<?= $clase['CAPACIDAD'] ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="acciones-container">
                                    <?php if ($clase['PUEDE_CALIFICAR']): ?>
                                        <?php if ($clase['YA_CALIFICO']): ?>
                                            <div class="ya-calificado">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Ya calificaste esta clase
                                            </div>
                                        <?php else: ?>
                                            <button type="button" class="btn-calificar" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalCalificar"
                                                    data-clase-id="<?= $clase['ID_CLASE'] ?>"
                                                    data-clase-nombre="<?= htmlspecialchars($clase['CURSO_NOMBRE']) ?>">
                                                <i class="fas fa-star"></i>
                                                Calificar Mentor
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button type="button" class="btn-calificar" disabled>
                                            <i class="fas fa-lock"></i>
                                            Clase no finalizada
                                        </button>
                                    <?php endif; ?>
                                    
                                    <a href="<?= BASE_URL ?>/public/index.php?accion=notas&clase=<?= $clase['ID_CLASE'] ?>" 
                                       class="btn-ver-notas">
                                        <i class="fas fa-chart-bar"></i>
                                        Ver Notas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalCalificar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i>
                    Calificar Mentor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="accion" value="calificar">
                    <input type="hidden" name="id_clase" id="modal_clase_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Clase:</label>
                        <p class="text-muted" id="modal_clase_nombre"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Puntuación:</label>
                        <div class="rating-stars">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <input type="hidden" name="puntuacion" id="puntuacion_input" required>
                        <small class="form-text text-muted">Haz clic en las estrellas para calificar</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comentario" class="form-label fw-bold">Comentario (opcional):</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3"
                                  placeholder="Comparte tu experiencia con el mentor..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn_enviar_calificacion" disabled>
                        <i class="fas fa-paper-plane me-2"></i>
                        Enviar Calificación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalCalificar = document.getElementById('modalCalificar');
    const stars = document.querySelectorAll('.star');
    const puntuacionInput = document.getElementById('puntuacion_input');
    const btnEnviar = document.getElementById('btn_enviar_calificacion');
    
    modalCalificar.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const claseId = button.getAttribute('data-clase-id');
        const claseNombre = button.getAttribute('data-clase-nombre');
        
        document.getElementById('modal_clase_id').value = claseId;
        document.getElementById('modal_clase_nombre').textContent = claseNombre;
        
        stars.forEach(star => star.classList.remove('active'));
        puntuacionInput.value = '';
        btnEnviar.disabled = true;
    });
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-value'));
            puntuacionInput.value = rating;
            
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
            

            btnEnviar.disabled = false;
        });
        
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.getAttribute('data-value'));
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    });
    
    document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
        const currentRating = parseInt(puntuacionInput.value) || 0;
        stars.forEach((star, index) => {
            if (index < currentRating) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#ddd';
            }
        });
    });
});
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>