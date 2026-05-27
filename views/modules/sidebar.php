<?php
/**
 * SIDEBAR DINÁMICO ESTILO OPUS 24 PREMIUM
 * Para integrar datos reales, asegúrate de que tu controlador ejecute la consulta SQL
 * (ej. SELECT id, codigo, nombre FROM obras) y guarde el resultado en la variable $proyectos.
 */

// CONTROL DE SEGURIDAD: Inicialización de respaldo si la base de datos no ha enviado datos aún
if (!isset($proyectos) || empty($proyectos)) {
    $proyectos = [
        ['id' => 1, 'codigo' => 'IPTE-2026-001', 'nombre' => 'Automatización Planta Industrial Norte'],
        ['id' => 2, 'codigo' => 'IPTE-2026-002', 'nombre' => 'Mantenimiento Subestación Eléctrica Sur'],
        ['id' => 3, 'codigo' => 'IPTE-2026-003', 'nombre' => 'Instalación de CCTV Planta Bajío']
    ];
}

// Capturamos las variables del estado actual desde la URL de forma segura
$proyectoActivoId = isset($_GET['project']) ? intval($_GET['project']) : 1;
$vistaActiva      = isset($_GET['view']) ? $_GET['view'] : 'wbs';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #1e282c; font-family: 'Inter', sans-serif;">
  
  <a href="index.php" class="brand-link border-bottom border-secondary text-center py-3">
    <span class="brand-text font-weight-bold text-white" style="letter-spacing: 1px; font-size: 1.1rem;">
      <i class=""></i>IPTE Soluciones
    </span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-2 mb-3 d-flex align-items-center border-bottom border-secondary">
      <div class="info w-100">
        <small class="text-uppercase text-muted font-weight-bold d-block px-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">
          <i class="fas fa-search-nodes mr-1 text-info"></i> Explorador de Vistas
        </small>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
        
        <?php foreach ($proyectos as $p): 
            $esProyectoActivo = ($p['id'] == $proyectoActivoId);
        ?>
          <li class="nav-item <?php echo $esProyectoActivo ? 'menu-open' : ''; ?>">
            
            <a href="#" class="nav-link <?php echo $esProyectoActivo ? 'active' : ''; ?>" 
               style="<?php echo $esProyectoActivo ? 'background-color: #2c3b41 !important; border-left: 4px solid #3498db;' : ''; ?>">
              <i class="nav-icon fas fa-folder <?php echo $esProyectoActivo ? 'text-warning' : 'text-secondary opacity-75'; ?>"></i>
              <p class="text-truncate font-weight-bold" style="max-width: 175px;" title="<?php echo $p['codigo'] . ' - ' . $p['nombre']; ?>">
                <?php echo $p['codigo']; ?>
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            
            <ul class="nav nav-treeview">
              
              <li class="nav-item menu-open mt-1">
                <span class="text-uppercase text-muted font-weight-bold pl-4 d-block" style="font-size: 0.7rem; letter-spacing: 0.3px;">
                  <i class="fas fa-folder-open text-warning mr-1" style="font-size: 0.75rem;"></i> Propuesta
                </span>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="index.php?project=<?php echo $p['id']; ?>&view=wbs" 
                       class="nav-link <?php echo ($esProyectoActivo && $vistaActiva == 'wbs') ? 'active bg-primary text-white font-weight-bold' : 'text-white-50'; ?> py-1">
                      <i class="far fa-list-alt nav-icon text-info" style="font-size: 0.8rem;"></i>
                      <p>Presupuesto programable</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link text-muted disabled py-1" style="cursor: not-allowed; opacity: 0.5;">
                      <i class="fas fa-chart-pie nav-icon" style="font-size: 0.8rem;"></i>
                      <p>Análisis de presupuesto</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item menu-open mt-2">
                <span class="text-uppercase text-muted font-weight-bold pl-4 d-block" style="font-size: 0.7rem; letter-spacing: 0.3px;">
                  <i class="fas fa-box-open text-info mr-1" style="font-size: 0.75rem;"></i> Insumos
                </span>
                <ul class="nav nav-treeview">
                  <li class="nav-item"><a href="#" class="nav-link text-muted disabled py-1" style="cursor: not-allowed; opacity: 0.5;"><p>• Materiales</p></a></li>
                  <li class="nav-item"><a href="#" class="nav-link text-muted disabled py-1" style="cursor: not-allowed; opacity: 0.5;"><p>• Mano de obra</p></a></li>
                  <li class="nav-item"><a href="#" class="nav-link text-muted disabled py-1" style="cursor: not-allowed; opacity: 0.5;"><p>• Herramienta / Equipo</p></a></li>
                </ul>
              </li>

              <li class="nav-item menu-open mt-2 mb-2">
                <span class="text-uppercase text-muted font-weight-bold pl-4 d-block" style="font-size: 0.7rem; letter-spacing: 0.3px;">
                  <i class="fas fa-business-time text-success mr-1" style="font-size: 0.75rem;"></i> Ejecución
                </span>
                <ul class="nav nav-treeview">
                  <li class="nav-item"><a href="#" class="nav-link text-muted disabled py-1" style="cursor: not-allowed; opacity: 0.5;"><p>• Estimaciones</p></a></li>
                </ul>
              </li>

            </ul>
          </li>
        <?php endforeach; ?>

        <li class="nav-header text-uppercase text-muted mt-4" style="font-size: 0.65rem; letter-spacing: 1px;">Estatus</li>
        <li class="nav-item px-3 py-1">
          <span class="badge badge-success d-block text-left p-2 shadow-sm" style="background-color: rgba(40, 167, 69, 0.15); color: #2ecc71; border: 1px solid rgba(40, 167, 69, 0.3);">
            <i class="fas fa-database mr-2"></i> SQL Server Conectado
          </span>
        </li>
      </ul>
    </nav>
  </div>
</aside>