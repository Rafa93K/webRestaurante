<?php

/* ---------- CONEXIÓN BD ---------- */
$server = "localhost";
$user = "fogon";
$pw = "fogon2019+";
$bd = "fogon";

$pdo = new PDO(
    "mysql:host=$server;dbname=$bd;charset=utf8",
    $user,
    $pw,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
?>