<?php
require_once 'controllers/PresupuestoController.php';

$controller = new PresupuestoController();

// Interceptar peticiones AJAX para desglose
if (isset($_GET['action']) && $_GET['action'] == 'obtener_hijos' && isset($_GET['id'])) {
    // Configura la cabecera para devolver JSON
    header('Content-Type: application/json');
    $controller->obtenerHijosJSON($_GET['id']);
    exit; // Detiene el script para no cargar HTML
}

// Si no es AJAX, muestra la página normal
$controller->mostrarDashboard();