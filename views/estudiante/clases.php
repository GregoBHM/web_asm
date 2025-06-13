<?php 
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-graduation-cap"></i> Mis Clases
                    </h4>
                </div>
                <div class="card-body">
                    
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
                    
                    <?php if (empty($clases)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No tienes clases inscritas</h5>
                            <p class="text-muted">Contacta con tu coordinador académico para inscribirte en clases.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Código</th>
                                        <th>Curso</th>
                                        <th>Ciclo</th>
                                        <th>Aula</th>
                                        <th>Horario</th>
                                        <th>Fecha Inicio</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clases as $clase): ?>
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
                                                    echo date('d/m/Y H:i', strtotime($clase['FECHA_INICIO']));
                                                } else {
                                                    echo '<span class="text-muted">Por definir</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($clase['ESTADO'] == 1): ?>
                                                    <span class="badge bg-success">Activa</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactiva</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="?action=ver_detalle&id=<?php echo $clase['ID_CLASE']; ?>" 
                                                       class="btn btn-info btn-sm" 
                                                       title="Ver detalles">
                                                        <i class="fas fa-eye"></i> Detalles
                                                    </a>
                                                    <?php if (!$this->claseModel->verificarCalificacionExistente($_SESSION['id_estudiante'], $clase['ID_CLASE'])): ?>
                                                        <a href="?action=calificar_mentor&id=<?php echo $clase['ID_CLASE']; ?>" 
                                                           class="btn btn-warning btn-sm" 
                                                           title="Calificar mentor">
                                                            <i class="fas fa-star"></i> Calificar
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-success btn-sm" disabled title="Ya calificado">
                                                            <i class="fas fa-check"></i> Calificado
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Información adicional -->
                        <div class="mt-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-book fa-2x text-primary mb-2"></i>
                                            <h5>Total de Clases</h5>
                                            <h3 class="text-primary"><?php echo count($clases); ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <h5>Clases Activas</h5>
                                            <h3 class="text-success">
                                                <?php echo count(array_filter($clases, function($c) { return $c['ESTADO'] == 1; })); ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                            <h5>Por Calificar</h5>
                                            <h3 class="text-warning">
                                                <?php 
                                                $porCalificar = 0;
                                                foreach($clases as $clase) {
                                                    if (!$this->claseModel->verificarCalificacionExistente($_SESSION['id_estudiante'], $clase['ID_CLASE'])) {
                                                        $porCalificar++;
                                                    }
                                                }
                                                echo $porCalificar;
                                                ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group .btn {
    margin-right: 2px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.75em;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.bg-light .card-body i {
    opacity: 0.8;
}
</style>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>