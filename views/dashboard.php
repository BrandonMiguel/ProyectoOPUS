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
        --border-color: #dee2e6;
        
        --color-nivel-2: #3498db; 
        --bg-nivel-2: #f1f7fc;    
        
        --color-nivel-3: #2ecc71; 
        --bg-nivel-3: #f4fbf7;    
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--ipte-bg); }
    .card { border: none; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; }
    .content-wrapper { background-color: var(--ipte-bg); }

    /* TABLA PRINCIPAL (NIVEL 1) */
    #tablaIPTE { border-collapse: collapse; border: 1px solid var(--border-color); }
    #tablaIPTE thead th {
        background-color: #ffffff;
        color: var(--ipte-blue);
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid var(--border-color);
        border-bottom: 3px solid var(--ipte-blue) !important;
        padding: 12px;
    }
    
    #tablaIPTE tbody tr.parent-row { background-color: #f8f9fa; }
    #tablaIPTE tbody tr.parent-row:hover { background-color: #f1f3f5; }
    #tablaIPTE td { padding: 11px 14px; vertical-align: middle; border: 1px solid var(--border-color); }

    .btn-expand {
        width: 24px; height: 24px; border-radius: 4px; display: flex; align-items: center; justify-content: center;
        background: #ffffff; color: var(--ipte-blue); border: 1px solid #bacede; font-weight: bold; font-size: 0.80rem;
        cursor: pointer; transition: 0.2s;
    }
    .btn-expand:hover { background: var(--ipte-blue); color: white; border-color: var(--ipte-blue); }

    /* =======================================================
       MEJORAS DE UX (EXPERIENCIA DE USUARIO)
       ======================================================= */
    
    /* =======================================================
       MEJORAS DE UX (EXPERIENCIA DE USUARIO)
       ======================================================= */
    
    /* 1. Tipografía Tabular: Alineación perfecta de decimales */
    .tabular-nums {
        font-variant-numeric: tabular-nums;
        letter-spacing: 0.2px;
    }

    /* 2. Scroll Interno Nivel 2 (Subcapítulos) + Antidescontrol */
    .scroll-nivel-2 {
        max-height: 450px; 
        overflow-y: auto !important;
        overflow-x: hidden;
        overscroll-behavior: contain; /* Evita que se mueva el scroll general de la página */
    }

    /* 3. Scroll Interno Nivel 3 (Conceptos) + Antidescontrol */
    .scroll-nivel-3 {
        max-height: 350px; 
        overflow-y: auto !important; 
        overflow-x: hidden; 
        overscroll-behavior: contain; /* Bloquea el encadenamiento de scroll aquí también */
    }

    /* 4. Cabeceras Pegajosas (Sticky Headers) */
    .table-nested thead th {
        position: sticky;
        top: 0;
        z-index: 2; 
        box-shadow: 0 2px 2px -1px rgba(0,0,0,0.2); 
    }

    /* 4. Cabeceras Pegajosas (Sticky Headers) */
    .table-nested thead th {
        position: sticky;
        top: 0;
        z-index: 2; 
        box-shadow: 0 2px 2px -1px rgba(0,0,0,0.2); 
    }

    /* ======================================================= */

    .marco-nivel-2 {
        background-color: var(--bg-nivel-2);
        border: 2px solid var(--color-nivel-2) !important; 
        border-radius: 6px; 
        padding: 0 !important; /* Ajustado a 0 para que la barra de scroll pegue al borde derecho */
        margin: 10px 15px; 
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        overflow: hidden; /* Mantiene bordes redondeados limpios */
    }
    .bg-subcap-header th { background-color: #e3ebf3 !important; color: #2c3e50; font-size: 0.7rem; text-transform: uppercase; border: 1px solid #bacede !important; }

    .marco-nivel-3 {
        background-color: var(--bg-nivel-3);
        border: 2px solid var(--color-nivel-3) !important; 
        border-radius: 6px; 
        padding: 0 !important; 
        margin: 8px 12px; 
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        overflow: hidden; 
    }
    .bg-concept-header th { background-color: #d1d8e0 !important; color: #2c3e50; font-size: 0.7rem; text-transform: uppercase; border: 1px solid #bca3ca !important; }

    .table-nested { margin-bottom: 0; background-color: #ffffff !important; }
    .table-nested td { border: 1px solid var(--border-color) !important; padding: 7px 10px; font-size: 0.85rem; }
    
    .text-concepto { color: #5a6268; font-size: 0.82rem; }
    .text-total { color: #27ae60; font-weight: 700; }


    /* Corrección de alineación para tablas con scroll interno */
    .scroll-nivel-2 .table-nested,
    .scroll-nivel-3 .table-nested {
        width: 100% !important;
        table-layout: fixed; /* Fuerza a respetar los anchos asignados en TH */
    }

    /* Asegura que el texto largo de la descripción no rompa las celdas */
    .table-nested td {
        white-space: normal;
        word-wrap: break-word;
    }
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
          <div class="col-sm-6">
            <span class="badge badge-primary px-3 py-1 mb-2 text-uppercase font-weight-bold" style="font-size:0.65rem; background-color: var(--ipte-blue);">Proyecto Activo: IPTE-2026-001</span>
            <h1 class="m-0 font-weight-bold text-dark" style="font-size: 1.5rem;">Automatización Planta Industrial Norte</h1>
          </div>
          <div class="col-sm-6 text-right">
             <button class="btn btn-white bg-white rounded shadow-sm border px-3 mr-2 text-primary font-weight-bold btn-sm" id="btnContraerTodo">
                <i class="fas fa-compress-arrows-alt mr-1"></i> Contraer Todo
             </button>
             <button class="btn btn-white bg-white rounded shadow-sm border px-3 mr-2 text-muted btn-sm" id="btnSincronizar">
                <i class="fas fa-sync-alt mr-1"></i> Sincronizar
             </button>
             <div class="d-inline-block p-2 bg-white rounded shadow-sm px-3 border text-secondary font-weight-bold small">
                <i class="fas fa-lock text-warning mr-1"></i> Solo Lectura
             </div>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card shadow-sm border">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table id="tablaIPTE" class="table table-bordered mb-0 w-100">
                <thead>
                  <tr>
                    <th width="4%" class="text-center">#</th>
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
                    <td class="text-center details-control align-middle"><button class="btn-expand mx-auto"><i class="fas fa-plus"></i></button></td>
                    <td class="font-weight-bold align-middle" style="color: var(--ipte-blue);"><?php echo $fila['codigo']; ?></td>
                    <td class="align-middle text-dark font-weight-bold"><i class="fas fa-folder text-warning mr-2 opacity-75"></i> <?php echo $fila['descripcion']; ?></td>
                    <td></td><td></td><td></td>
                    <td class="text-right text-total align-middle tabular-nums">$<?php echo number_format($importe_total, 2); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="card-footer bg-light p-3 border-top">
            <div class="row align-items-center">
               <div class="col-md-6">
                 <p class="mb-0 text-muted small"><i class="fas fa-info-circle mr-1"></i> Tipografía tabular y scroll inteligente multinivel aplicados.</p>
               </div>
               <div class="col-md-6 text-right">
                 <span class="text-muted text-uppercase small font-weight-bold mr-3">Costo Directo Total:</span>
                 <span class="h4 font-weight-bold text-dark mb-0 tabular-nums">$<?php echo number_format($monto_acumulado, 2); ?></span>
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
      "responsive": true, "lengthChange": false, "pageLength": 15, "ordering": false,
      "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-MX.json" },
      "dom": '<"p-3 border-bottom"<"row"<"col-sm-12"f>>>t<"p-3"<"row"<"col-sm-12"p>>>',
    });

    // Lógica para el botón "Contraer Todo"
    $('#btnContraerTodo').click(function() {
        $('.child-row-sub').remove();
        $('.details-control-sub i').removeClass('fa-minus').addClass('fa-plus');
        
        table.rows().every(function() {
            if (this.child.isShown()) {
                this.child.hide();
                $(this.node()).removeClass('shown');
                $(this.node()).find('td.details-control button i').removeClass('fa-minus').addClass('fa-plus');
            }
        });
    });

    function format(d, level) {
        let isConcept = (level === 'sub');
        
        // Asignación de clases de scroll correspondientes para Nivel 2 y Nivel 3
        let marcoClass  = isConcept ? 'marco-nivel-3 scroll-nivel-3' : 'marco-nivel-2 scroll-nivel-2';
        let headerClass = isConcept ? 'bg-concept-header' : 'bg-subcap-header';
        
        let subTable = '<div class="' + marcoClass + '"><table class="table table-nested table-bordered table-striped w-100 mb-0">';
        subTable += '<thead class="'+headerClass+'"><tr><th width="4%" class="text-center"></th><th width="12%">Código</th><th>Descripción / Especificación</th><th width="8%" class="text-center">Unidad</th><th width="10%" class="text-right">Cant.</th><th width="12%" class="text-right">P.U.</th><th width="15%" class="text-right">Importe</th></tr></thead><tbody>';
        
        d.forEach(function(item) {
            let total = parseFloat(item.importe_total || 0); 
            let esConcepto = (item.tipo === 'Concepto' || isConcept);
            
            let textClass = esConcepto ? 'text-concepto' : 'text-dark font-weight-bold';
            let iconCode  = esConcepto ? '<i class="far fa-file-alt text-muted mr-2"></i>' : '<i class="fas fa-folder-open text-info mr-2"></i>';
            let actionBtn = esConcepto ? '<i class="fas fa-minus text-muted small"></i>' : '<button class="btn btn-xs btn-outline-info details-control-sub" data-id="'+item.id+'"><i class="fas fa-plus"></i></button>';

            subTable += '<tr>';
            subTable += '<td class="text-center align-middle">' + actionBtn + '</td>';
            subTable += '<td class="font-weight-bold align-middle" style="color:#0f2c59;">'+item.codigo+'</td>';
            subTable += '<td class="align-middle ' + textClass + '">' + iconCode + item.descripcion+'</td>';
            subTable += '<td class="text-center align-middle ' + textClass + '">'+(esConcepto ? item.unidad : '')+'</td>';
            
            subTable += '<td class="text-right font-weight-bold align-middle text-dark tabular-nums">'+(esConcepto ? parseFloat(item.cantidad).toLocaleString('es-MX', {minimumFractionDigits: 2}) : '')+'</td>';
            subTable += '<td class="text-right align-middle text-muted tabular-nums">'+(esConcepto ? '$'+parseFloat(item.precio_unitario).toLocaleString('es-MX', {minimumFractionDigits: 2}) : '')+'</td>';
            subTable += '<td class="text-right font-weight-bold text-success align-middle tabular-nums">$'+total.toLocaleString('es-MX', {minimumFractionDigits: 2})+'</td>';
            subTable += '</tr>';
        });
        subTable += '</tbody></table></div>';
        return subTable;
    }

    // Nivel 1 -> Nivel 2
    $('#tablaIPTE tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr'); var row = table.row( tr ); var btnIcon = $(this).find('button i'); var rowId = tr.data('id');
        if ( row.child.isShown() ) { row.child.hide(); tr.removeClass('shown'); btnIcon.removeClass('fa-minus').addClass('fa-plus'); } 
        else {
            btnIcon.removeClass('fa-plus').addClass('fa-spinner fa-spin');
            $.get('index.php?action=obtener_hijos&id=' + rowId, function(response) {
                if(response.status === 'ok') {
                    row.child(format(response.data, 'main')).show();
                    tr.next().find('td').css({'padding': '0', 'background-color': '#ffffff', 'border': 'none'}); 
                    tr.addClass('shown'); btnIcon.removeClass('fa-spinner fa-spin').addClass('fa-minus');
                }
            });
        }
    });

    // Nivel 2 -> Nivel 3
    $('#tablaIPTE tbody').on('click', '.details-control-sub', function () {
        var tr = $(this).closest('tr'); var rowId = $(this).data('id'); var btn = $(this).find('i');
        if (tr.next('.child-row-sub').length) { tr.next('.child-row-sub').remove(); btn.removeClass('fa-minus').addClass('fa-plus'); } 
        else {
            btn.removeClass('fa-plus').addClass('fa-spinner fa-spin');
            $.get('index.php?action=obtener_hijos&id=' + rowId, function(response) {
                if(response.status === 'ok') {
                    let childRow = '<tr class="child-row-sub"><td colspan="7" class="p-0 border-0" style="background-color: transparent;">' + format(response.data, 'sub') + '</td></tr>';
                    tr.after(childRow); btn.removeClass('fa-spinner fa-spin').addClass('fa-minus');
                }
            });
        }
    });
    
    $('#btnSincronizar').click(function(){ location.reload(); });
  });
</script>
</body>
</html>