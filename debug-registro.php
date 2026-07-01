<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=bolsa_trabajo', 'root', '');
$stmt = $pdo->query("SHOW CREATE TABLE registro_empresa_producto");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($row);
?>
