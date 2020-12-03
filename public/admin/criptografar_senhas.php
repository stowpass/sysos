<?php

require_once __DIR__ . '/../includes/funcoes.php';

$db = conectarBanco();
$stmt = $db->prepare('SELECT id, senha FROM tb_admin WHERE NOT senha LIKE \'$2y$10$%\'');
$stmt->execute();

$usrs = $stmt->fetchAll();
$total = count($usrs);

$stmt = $db->prepare('UPDATE tb_admin SET senha = ? WHERE id = ?');

try {
    foreach ($usrs as $usr) {
        $senha = password_hash($usr->senha, PASSWORD_DEFAULT);
        $stmt->bindParam(1, $senha, PDO::PARAM_STR);
        $stmt->bindParam(2, $usr->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    echo "Registros atualizados: $total";
} catch (\PDOException $e) {
    echo "Erro: {$e->getMessage()}";
}
