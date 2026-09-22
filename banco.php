<?php

require_once __DIR__ . "/conexao.php";

/*
========================================
TABELA DE USUÁRIOS
========================================
*/

$db->exec("
    CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        senha TEXT NOT NULL,
        acessibilidade TEXT DEFAULT '',
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");


/*
========================================
TABELA DE EVENTOS
========================================
*/

$db->exec("
    CREATE TABLE IF NOT EXISTS eventos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        descricao TEXT NOT NULL,
        local TEXT NOT NULL,
        data_evento TEXT NOT NULL,
        horario TEXT NOT NULL,
        preco REAL NOT NULL DEFAULT 0,
        acessibilidade TEXT DEFAULT '',
        audiodescricao TEXT DEFAULT '',
        mapa_sensorial TEXT DEFAULT ''
    )
");


/*
========================================
TABELA DE INGRESSOS
========================================
*/

$db->exec("
    CREATE TABLE IF NOT EXISTS ingressos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario_id INTEGER NOT NULL,
        evento_id INTEGER NOT NULL,
        codigo TEXT NOT NULL UNIQUE,
        comprado_em DATETIME DEFAULT CURRENT_TIMESTAMP,

        FOREIGN KEY (usuario_id)
            REFERENCES usuarios(id)
            ON DELETE CASCADE,

        FOREIGN KEY (evento_id)
            REFERENCES eventos(id)
            ON DELETE CASCADE
    )
");


/*
========================================
EVENTOS DE TESTE
========================================
*/

$totalEventos = $db
    ->query("SELECT COUNT(*) FROM eventos")
    ->fetchColumn();


if ($totalEventos == 0) {

    $sql = $db->prepare("
        INSERT INTO eventos (
            nome,
            descricao,
            local,
            data_evento,
            horario,
            preco,
            acessibilidade,
            audiodescricao,
            mapa_sensorial
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");


    $sql->execute([
        "Show do Luan Santana",
        "Um grande evento musical com recursos de acessibilidade.",
        "São Paulo - SP",
        "2026-09-20",
        "20:00",
        150.00,
        "Intérprete de Libras, espaço PCD e acessibilidade física.",
        "Audiodescrição disponível.",
        "Mapa sensorial disponível."
    ]);


    $sql->execute([
        "Festival Acesso Livre",
        "Festival com música, cultura e inclusão.",
        "São Paulo - SP",
        "2026-10-10",
        "18:00",
        80.00,
        "Libras, audiodescrição e espaço acessível.",
        "Audiodescrição disponível.",
        "Mapa sensorial disponível."
    ]);

}


echo "Banco de dados criado com sucesso!";

?>