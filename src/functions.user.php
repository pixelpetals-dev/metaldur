<?php
function createUser(PDO $pdo, string $u, string $e, string $plain): int
{
    $hash = password_hash($plain, PASSWORD_DEFAULT);
    $sql  = 'INSERT INTO users (username,email,password_hash) VALUES (?,?,?)';
    $pdo->prepare($sql)->execute([$u, $e, $hash]);
    return (int)$pdo->lastInsertId();
}
