<?php
class Presupuesto {
    private $conn;
    // Cambiamos la tabla por la VISTA optimizada para las lecturas directas
    private $view_name = "vista_visor_opus"; 

    public function __construct($db) {
        $this->conn = $db;
    }

    // Trae las raíces (Capítulos) y calcula su dinero total usando la vista
    public function obtenerRaices() {
        $query = "SELECT * FROM " . $this->view_name . " WHERE parent_id IS NULL ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $raices = $stmt->fetchAll(PDO::FETCH_ASSOC); // Forzamos formato asociativo limpio

        // Calcular el total acumulado para cada capítulo
        foreach ($raices as &$raiz) {
            $raiz['importe_total'] = $this->calcularSumaArbol($raiz['id']);
        }
        return $raices;
    }

    // Trae los desgloses de forma ultra rápida gracias al índice parent_id
    public function obtenerHijos($parent_id) {
        $query = "SELECT * FROM " . $this->view_name . " WHERE parent_id = :parent_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
        $stmt->execute();
        $hijos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($hijos as &$hijo) {
            // OPTIMIZACIÓN: Si es Concepto o Insumo, ya NO hacemos la multiplicación en PHP.
            // La vista 'vista_visor_opus' ya trae el campo 'importe_total' calculado desde la DB.
            if ($hijo['tipo'] !== 'Concepto' && $hijo['tipo'] !== 'Insumo') {
                // Si es un Subcapítulo, ejecuta la suma recursiva hacia abajo
                $hijo['importe_total'] = $this->calcularSumaArbol($hijo['id']);
            }
        }
        return $hijos;
    }

    // Función Recursiva (Ahora vuela gracias a que tu DB ya cuenta con el índice idx_parent_id)
    private function calcularSumaArbol($id) {
        $query = "WITH RECURSIVE jerarquia AS (
                    SELECT id, cantidad, precio_unitario FROM presupuestos WHERE id = :id
                    UNION ALL
                    SELECT p.id, p.cantidad, p.precio_unitario FROM presupuestos p
                    INNER JOIN jerarquia j ON p.parent_id = j.id
                  )
                  SELECT SUM(cantidad * precio_unitario) as gran_total FROM jerarquia";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['gran_total'] ? (float)$row['gran_total'] : 0.00;
        } catch (Exception $e) {
            return 0.00; // Fallback de seguridad
        }
    }
}
?>