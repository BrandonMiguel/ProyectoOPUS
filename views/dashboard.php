<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IPTE Soluciones | Visor de Ingeniería OPUS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.13.4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

  <style>
    :root {
        --ipte-blue: #0f2c59;
        --ipte-accent: #3498db;
        --ipte-bg: #f4f7fa;
        --ipte-text: #2c3e50;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--ipte-bg);
        color: var(--ipte-text);
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        margin-bottom: 25px;
    }

    .content-wrapper { background-color: var(--ipte-bg); }

    /* TABLA ESTILO MATRIZ PROFESIONAL */
    #tablaIPTE { border-collapse: separate; border-spacing: 0 6px; }
    #tablaIPTE thead th {
        color: #8391a2;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        border: none;
        padding-bottom: 12px;
    }

    #tablaIPTE tbody tr.parent-row { background-color: #ffffff; transition: 0.2s; }
    #tablaIPTE tbody tr.parent-row:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 44, 89, 0.08);
    }

    #tablaIPTE td { padding: 14px 16px; vertical-align: middle; border: none; }
    #tablaIPTE td:first-child { border-radius: 6px 0 0 6px; }
    #tablaIPTE td:last-child { border-radius: 0 6px 6px 0; }

    /* BOTÓN INTERACTIVO MÁS / MENOS (REMPLAZA EL ÍCONO ROTO) */
    .btn-expand {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4f8;
        color: var(--ipte-blue);
        border: 1px solid #dbe3eb;
        font-weight: bold;
        font-size: 0.85rem;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-expand:hover { 
        background: var(--ipte-blue); 
        color: white;
        border-color: var(--ipte-blue);
    }

    .nested-wrapper {
        padding: 15px 15px 15px 45px !important;
        background-color: #f8fbff;
        border-radius: 0 0 8px 8px;
    }

    .table-nested { background: white; border-radius: 8px; border: 1px solid #e1e8f0 !important; }
    .text-total { color: #27ae60; font-weight: 700; }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php include_once 'views/modules/header.php'; ?>
  <?php include_once 'views/modules/sidebar.php'; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row align-items-center mt-2">
          <div class="col-sm-8">
            <span class="badge badge-primary px-3 py-1 mb-2 text-uppercase font-weight-bold" style="font-size:0.65rem; background-color: var(--ipte-blue);">
              Proyecto Activo: IPTE-2026-001
            </span>
            <h1 class="m-0 font-weight-bold text-dark" style="font-size: 1.5rem;">Automatización Planta Industrial Norte</h1>
          </div>
          <div class="col-sm-4 text-right">
             <button class="btn btn-white bg-white rounded-pill shadow-sm border px-3 mr-2 text-muted btn-sm" id="btnSincronizar">
                <i class="fas fa-sync-alt mr-1"></i> Sincronizar Base
             </button>
             <div class="d-inline-block p-2 bg-white rounded-pill shadow-sm px-3 border text-secondary font-weight-bold small">
                <i class="fas fa-lock text-warning mr-1"></i> Solo Lectura
             </div>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        <div class="card shadow-sm border-0">
          <div class="card-body p-3">
            <div class="table-responsive">
              <table id="tablaIPTE" class="table w-100">
                <thead>
                  <tr>
                    <th width="4%"></th>
                    <th width="12%">Código WBS</th>
                    <th>Descripción Estructural (Nivel 1)</th>
                    <th width="8%" class="text-center">Unidad</th>
                    <th width="10%" class="text-right">Cantidad</th>
                    <th width="12%" class="text-right">P.U. (MXN)</th>
                    <th width="15%" class="text-right">Importe Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $monto_acumulado = 0; 
                  foreach ($presupuestos as $fila): 
                      $importe_total = isset($fila['importe_total']) ? $fila['importe_total'] : 0;
                      $monto_acumulado += $importe_total;
                  ?>
                  <tr data-id="<?php echo $fila['id']; ?>" class="parent-row">
                    <td class="text-center details-control">
                       <button class="btn-expand"><i class="fas fa-plus"></i></button>
                    </td>
                    <td class="font-weight-bold" style="color: var(--ipte-blue);"><?php echo $fila['codigo']; ?></td>
                    <td>
                      <span class="text-dark font-weight-bold">
                        <i class="fas fa-folder text-warning mr-2 opacity-75"></i> 
                        <?php echo $fila['descripcion']; ?>
                      </span>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right text-total">$<?php echo number_format($importe_total, 2); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="card-footer bg-white p-4 border-top">
            <div class="row align-items-center">
               <div class="col-md-6">
                 <p class="mb-0 text-muted small"><i class="fas fa-info-circle mr-1"></i> Presupuesto Estructural extraído de forma segura desde OPUS Base Corporativa.</p>
               </div>
               <div class="col-md-6 text-right">
                 <span class="text-muted text-uppercase small font-weight-bold mr-3">Monto Total Directo Evaluado:</span>
                 <span class="h3 font-weight-bold text-dark">$<?php echo number_format($monto_acumulado, 2); ?></span>
               </div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>

  <?php include_once 'views/modules/footer.php'; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function() {
    var table = $('#tablaIPTE').DataTable({
      "responsive": true, 
      "lengthChange": false, 
      "pageLength": 15, 
      "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-MX.json" },
      "columnDefs": [ { "orderable": false, "targets": 0 } ],
      "dom": '<"row"<"col-sm-12"f>>t<"row"<"col-sm-12"p>>',
    });

    function format(d, level) {
        let isConcept = (level === 'sub');
        let tableHeaderClass = isConcept ? 'bg-dark' : 'bg-light';
        
        let subTable = '<div class="table-responsive"><table class="table table-sm table-hover w-100 table-nested text-xs">';
        subTable += '<thead class="'+tableHeaderClass+'"><tr><th width="5%"></th><th width="15%">Código</th><th>Descripción / Especificación</th><th width="10%">Unidad</th><th width="10%" class="text-right">Cant.</th><th width="12%" class="text-right">P.U.</th><th width="15%" class="text-right">Importe</th></tr></thead><tbody>';
        
        d.forEach(function(item) {
            let total = parseFloat(item.importe_total || 0); 
            let esConcepto = (item.tipo === 'Concepto');
            
            subTable += '<tr class="'+(esConcepto ? 'bg-white' : 'bg-light')+'">';
            subTable += '<td class="text-center">' + (esConcepto ? '<i class="fas fa-minus text-muted small"></i>' : '<button class="btn btn-xs btn-link details-control-sub" data-id="'+item.id+'"><i class="fas fa-plus-circle text-info"></i></button>') + '</td>';
            subTable += '<td class="font-weight-bold text-navy">'+item.codigo+'</td>';
            subTable += '<td>'+ (esConcepto ? '<i class="far fa-file-alt text-muted mr-2"></i>' : '<i class="fas fa-folder-open text-info mr-2"></i>') + item.descripcion+'</td>';
            subTable += '<td class="text-center">'+(esConcepto ? item.unidad : '')+'</td>';
            subTable += '<td class="text-right font-weight-bold text-dark">'+(esConcepto ? parseFloat(item.cantidad).toLocaleString('es-MX', {minimumFractionDigits: 2}) : '')+'</td>';
            subTable += '<td class="text-right text-muted">'+(esConcepto ? '$'+parseFloat(item.precio_unitario).toLocaleString('es-MX', {minimumFractionDigits: 2}) : '')+'</td>';
            subTable += '<td class="text-right font-weight-bold text-success">$'+total.toLocaleString('es-MX', {minimumFractionDigits: 2})+'</td>';
            subTable += '</tr>';
        });
        subTable += '</tbody></table></div>';
        return subTable;
    }

    // Evento de Interacción con el Primer Nivel (Capítulos)
    $('#tablaIPTE tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        var btnIcon = $(this).find('button i');
        var rowId = tr.data('id');

        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
            // Regresa a ícono de Más
            btnIcon.removeClass('fa-minus').addClass('fa-plus');
        } else {
            // Cambia transicionalmente a animación de carga
            btnIcon.removeClass('fa-plus').addClass('fa-spinner fa-spin');
            
            $.get('index.php?action=obtener_hijos&id=' + rowId, function(response) {
                if(response.status === 'ok') {
                    row.child('<div class="nested-wrapper">' + format(response.data, 'main') + '</div>').show();
                    tr.addClass('shown');
                    // Cambia a ícono de Menos al abrir exitosamente
                    btnIcon.removeClass('fa-spinner fa-spin').addClass('fa-minus');
                }
            });
        }
    });

    // Evento de Interacción Segundo Nivel
    $('#tablaIPTE tbody').on('click', '.details-control-sub', function () {
        var tr = $(this).closest('tr');
        var rowId = $(this).data('id');
        var btn = $(this).find('i');
        
        if (tr.next('.child-row-sub').length) {
            tr.next('.child-row-sub').remove();
            btn.removeClass('fa-minus-circle text-danger').addClass('fa-plus-circle text-info');
        } else {
            btn.removeClass('fa-plus-circle').addClass('fa-spinner fa-spin');
            $.get('index.php?action=obtener_hijos&id=' + rowId, function(response) {
                if(response.status === 'ok') {
                    let childRow = '<tr class="child-row-sub"><td colspan="7" class="p-4 bg-light border-0"><div class="pl-4">' + format(response.data, 'sub') + '</div></td></tr>';
                    tr.after(childRow);
                    btn.removeClass('fa-spinner fa-spin').addClass('fa-minus-circle text-danger');
                }
            });
        }
    });
    
    $('#btnSincronizar').click(function(){ location.reload(); });
  });
</script>
</body>
</html>