<?php
require_once 'config/database.php';
require_once 'models/Presupuesto.php';

class PresupuestoController {
    
    public function mostrarDashboard() {
        $error_db = false;
        $presupuestos = [];

        try {
            $db = (new Database())->getConnection();
            $model = new Presupuesto($db);
            // Solo cargamos los elementos raíz (Capítulos)
            $presupuestos = $model->obtenerRaices();
        } catch (Exception $e) {
            $error_db = true;
        }

        require_once 'views/dashboard.php';
    }

    // Nueva función para AJAX (Desglose multinivel)
    public function obtenerHijosJSON($parent_id) {
        try {
            $db = (new Database())->getConnection();
            $model = new Presupuesto($db);
            $hijos = $model->obtenerHijos($parent_id);
            echo json_encode(['status' => 'ok', 'data' => $hijos]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}