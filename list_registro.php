<?php
$pdo=new PDO('mysql:host=127.0.0.1;dbname=bolsa_trabajo','root','');
$stmt=$pdo->query('SELECT id_empresa_producto FROM registro_empresa_producto');
while($r=$stmt->fetch(PDO::FETCH_ASSOC)){
    echo $r['id_empresa_producto']."\n";
}
?>
