<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=bolsa_trabajo', 'root', '');
$stmt = $pdo->query("SELECT * FROM empresas_producto_rechazadas LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) { echo "no-row\n"; exit; }
echo "columns:\n"; print_r(array_keys($row));
echo "\nrow keys count: " . count($row) . "\n";
?>
