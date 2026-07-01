<?php
$pdo=new PDO('mysql:host=127.0.0.1;dbname=bolsa_trabajo','root','');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$id=7;
$producto = $pdo->query("SELECT * FROM empresas_producto_rechazadas WHERE id_rechazado=$id")->fetch(PDO::FETCH_ASSOC);
if(!$producto) { echo "no producto $id\n"; exit; }
print_r($producto);
$pdo->beginTransaction();
try {
    $insert = $pdo->prepare("INSERT INTO registro_empresa_producto (nombre_empresa,ruc,correo_electronico,telefono,responsable_representante,direccion,documento_validacion) VALUES (?,?,?,?,?,?,?)");
    $insert->execute([
        $producto['nombre_empresa'],$producto['ruc'],$producto['correo_electronico'],$producto['telefono'],$producto['responsable_representante'],$producto['direccion'],$producto['documento_validacion']
    ]);
    $empresaId = $pdo->lastInsertId();
    echo "empresaId=$empresaId\n";

    $insert2 = $pdo->prepare("INSERT INTO productos_empresa (id_empresa_producto,nombre_producto,descripcion,categoria,ubicacion_ciudad,telefono_contacto,redes_sociales,correo_contacto,direccion_atencion,imagen_producto,estado,fecha_inicio,fecha_fin) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $insert2->execute([
        $empresaId,
        $producto['nombre_producto'],
        $producto['descripcion'],
        $producto['categoria'],
        $producto['ubicacion_ciudad'],
        $producto['telefono_contacto'],
        $producto['redes_sociales'],
        $producto['correo_contacto'],
        $producto['direccion_atencion'],
        $producto['imagen_producto'],
        'Pendiente',
        $producto['fecha_inicio'],
        $producto['fecha_fin']
    ]);
    $pdo->commit();
    echo "ok\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "error: ".$e->getMessage()."\n";
}
?>
