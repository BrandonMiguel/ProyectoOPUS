<?php
// seeder.php - Ejecuta este archivo UNA SOLA VEZ en tu navegador
require_once 'config/database.php';

try {
    $db = (new Database())->getConnection();
    $db->exec("TRUNCATE TABLE presupuestos"); // Limpia la tabla
    
    echo "<h1>Generando Base de Datos Masiva para IPTE Soluciones...</h1>";
    
    // Generar 15 Capítulos Principales
    for ($c = 1; $c <= 15; $c++) {
        $stmt = $db->prepare("INSERT INTO presupuestos (parent_id, tipo, codigo, descripcion) VALUES (NULL, 'Capítulo', ?, ?)");
        $stmt->execute(["CAP-" . str_pad($c, 3, '0', STR_PAD_LEFT), "Capítulo Principal de Obra $c - Ingeniería IPTE"]);
        $capitulo_id = $db->lastInsertId();

        // Por cada Capítulo, generar 10 Subcapítulos
        for ($s = 1; $s <= 10; $s++) {
            $stmt = $db->prepare("INSERT INTO presupuestos (parent_id, tipo, codigo, descripcion) VALUES (?, 'Subcapítulo', ?, ?)");
            $stmt->execute([$capitulo_id, "SUBCAP-$c-$s", "Fase $s del Capítulo $c"]);
            $subcapitulo_id = $db->lastInsertId();

            // Por cada Subcapítulo, generar 30 Conceptos
            for ($co = 1; $co <= 30; $co++) {
                $precio = rand(100, 5000) + (rand(0, 99) / 100);
                $cantidad = rand(1, 500) + (rand(0, 99) / 100);
                $unidades = ['m2', 'm3', 'kg', 'Ton', 'Lote', 'Pza'];
                $unidad = $unidades[array_rand($unidades)];

                $stmt = $db->prepare("INSERT INTO presupuestos (parent_id, tipo, codigo, descripcion, unidad, cantidad, precio_unitario) VALUES (?, 'Concepto', ?, ?, ?, ?, ?)");
                $stmt->execute([$subcapitulo_id, "CONC-$c-$s-$co", "Suministro e instalación de material técnico variante $co", $unidad, $cantidad, $precio]);
            }
        }
    }
    echo "<h3 style='color:green;'>¡Éxito! Se generaron 4,665 registros jerárquicos. Cierra esto y ve a tu index.php</h3>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}