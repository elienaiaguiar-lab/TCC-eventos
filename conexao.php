<?php

$dbDir = __DIR__;

$dbFile = $dbDir . "/acesso_livre.db";

try {

    $db = new PDO("sqlite:" . $dbFile);

    $db->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $db->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    $db->exec("PRAGMA foreign_keys = ON");

} catch (PDOException $e) {

    die("Erro ao acessar o banco de dados: " . $e->getMessage());

}

?>