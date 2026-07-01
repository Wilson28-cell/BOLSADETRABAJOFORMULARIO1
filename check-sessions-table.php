<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=bolsa_trabajo', 'root', '');
$result = $pdo->query("SHOW TABLES LIKE 'sessions'");
$exists = $result->fetch();
echo $exists ? 'Tabla sessions existe' : 'Tabla sessions NO existe';
echo "\n";

// Si no existe, crearla
if (!$exists) {
    $pdo->exec("CREATE TABLE sessions (
        id VARCHAR(255) NOT NULL PRIMARY KEY,
        user_id BIGINT UNSIGNED NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        payload LONGTEXT NOT NULL,
        last_activity INT NOT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        KEY user_id (user_id),
        KEY last_activity (last_activity)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo 'Tabla sessions creada correctamente';
}
?>
