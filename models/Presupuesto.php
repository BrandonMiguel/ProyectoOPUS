<?php
class Presupuesto {
    private $conn;
    private $table_name = "presupuestos";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Trae las raíces (Capítulos) y calcula su dinero total
    public function obtenerRaices() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE parent_id IS NULL ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $raices = $stmt->fetchAll();

        // Magia: Calcular el total acumulado para cada capítulo
        foreach ($raices as &$raiz) {
            $raiz['importe_total'] = $this->calcularSumaArbol($raiz['id']);
        }
        return $raices;
    }

    // Trae los desgloses y calcula si tienen más dinero adentro
    public function obtenerHijos($parent_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE parent_id = :parent_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":parent_id", $parent_id);
        $stmt->execute();
        $hijos = $stmt->fetchAll();

        foreach ($hijos as &$hijo) {
            if ($hijo['tipo'] === 'Concepto' || $hijo['tipo'] === 'Insumo') {
                // Si es el nivel final, su total es Cantidad x Precio
                $hijo['importe_total'] = $hijo['cantidad'] * $hijo['precio_unitario'];
            } else {
                // Si es un Subcapítulo, suma lo de adentro
                $hijo['importe_total'] = $this->calcularSumaArbol($hijo['id']);
            }
        }
        return $hijos;
    }

    // Función Recursiva que suma todo el dinero de las ramas inferiores en milisegundos
    private function calcularSumaArbol($id) {
        // Usamos Common Table Expressions (CTE) soportado por XAMPP/MySQL/SQLServer
        $query = "WITH RECURSIVE jerarquia AS (
                    SELECT id, cantidad, precio_unitario FROM presupuestos WHERE id = :id
                    UNION ALL
                    SELECT p.id, p.cantidad, p.precio_unitario FROM presupuestos p
                    INNER JOIN jerarquia j ON p.parent_id = j.id
                  )
                  SELECT SUM(cantidad * precio_unitario) as gran_total FROM jerarquia";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row['gran_total'] ? $row['gran_total'] : 0;
        } catch (Exception $e) {
            return 0; // Fallback de seguridad
        }
    }
}
?>