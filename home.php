<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . "/banco/conexao.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$sql = $db->query("
    SELECT *
    FROM eventos
    ORDER BY data_evento ASC
    LIMIT 3
");

$eventos = $sql->fetchAll();

$nomeUsuario = $_SESSION["usuario_nome"] ?? "Usuário";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Início - Acesso Livre</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #F5FBFF;
    color: #334E68;
}

header {
    background: white;
    border-bottom: 1px solid #D9EAF5;
    padding: 18px 6%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo {
    color: #195A7A;
    font-size: 28px;
    font-weight: bold;
    text-decoration: none;
}

nav {
    display: flex;
    gap: 5px;
    align-items: center;
}

nav a {
    color: #334E68;
    text-decoration: none;
    padding: 9px 12px;
    font-size: 16px;
    border-radius: 7px;
}

nav a:hover {
    background: #EAF6FF;
}

.sair {
    background: #8CCCF6;
    color: #173042 !important;
}

main {
    max-width: 1150px;
    margin: auto;
    padding: 40px 25px;
}

.boas-vindas {
    background: white;
    border: 1px solid #D9EAF5;
    border-radius: 14px;
    padding: 30px;
    margin-bottom: 35px;
}

.boas-vindas h1 {
    margin: 0 0 10px;
    color: #195A7A;
    font-size: 34px;
}

.boas-vindas p {
    margin: 0;
    color: #526B7A;
    font-size: 18px;
}

.titulo {
    color: #195A7A;
    font-size: 28px;
    margin-bottom: 20px;
}

.eventos {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.evento {
    background: white;
    border: 1px solid #D9EAF5;
    border-radius: 12px;
    overflow: hidden;
}

.evento:hover {
    box-shadow: 0 5px 15px rgba(64, 124, 170, 0.12);
}

.imagem-evento {
    width: 100%;
    height: 190px;
    object-fit: cover;
    display: block;
}

.conteudo-evento {
    padding: 20px;
}

.evento h3 {
    color: #195A7A;
    font-size: 22px;
    margin: 0 0 10px;
}

.evento p {
    color: #526B7A;
    font-size: 16px;
    margin: 8px 0;
}

.evento strong {
    color: #195A7A;
}

.botao {
    display: block;
    width: 100%;
    margin-top: 18px;
    padding: 13px;
    background: #8CCCF6;
    color: #173042;
    text-align: center;
    text-decoration: none;
    border-radius: 9px;
    font-size: 17px;
    font-weight: bold;
}

.botao:hover {
    background: #73BEEB;
}

.sem-eventos {
    background: white;
    border: 1px solid #D9EAF5;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
}

.acessibilidade {
    background: white;
    border: 1px solid #D9EAF5;
    border-radius: 14px;
    padding: 25px;
    margin-top: 40px;
}

.acessibilidade h2 {
    margin: 0 0 8px;
    color: #195A7A;
    font-size: 24px;
}

.acessibilidade p {
    margin: 0;
    color: #526B7A;
}

.botoes {
    display: flex;
    gap: 10px;
    margin-top: 18px;
    flex-wrap: wrap;
}

.botoes button {
    padding: 11px 15px;
    background: white;
    border: 1px solid #8CCCF6;
    border-radius: 8px;
    color: #195A7A;
    font-size: 15px;
    cursor: pointer;
}

.botoes button:hover {
    background: #EAF6FF;
}

footer {
    margin-top: 30px;
    padding: 25px;
    background: #195A7A;
    color: white;
    text-align: center;
}

footer p {
    margin: 5px;
}

@media (max-width: 900px) {

    header {
        flex-direction: column;
        gap: 15px;
    }

    .eventos {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 600px) {

    main {
        padding: 25px 18px;
    }

    nav {
        justify-content: center;
        flex-wrap: wrap;
    }

    .eventos {
        grid-template-columns: 1fr;
    }

    .boas-vindas h1 {
        font-size: 28px;
    }

}

</style>

</head>

<body>

<header>

<a href="home.php" class="logo">
    Acesso Livre
</a>

<nav>

<a href="home.php">
    Início
</a>

<a href="eventos.php">
    Eventos
</a>

<a href="meus_ingressos.php">
    Meus ingressos
</a>

<a href="perfil.php">
    Meu perfil
</a>

<a href="logout.php" class="sair">
    Sair
</a>

</nav>

</header>

<main>

<section class="boas-vindas">

<h1>
    Olá, <?= htmlspecialchars($nomeUsuario) ?>! 👋
</h1>

<p>
    Bem-vindo ao <strong>Acesso Livre</strong>.
    Encontre eventos acessíveis e aproveite experiências
    pensadas para todos.
</p>

</section>

<h2 class="titulo">
    🎫 Próximos eventos
</h2>

<?php if (count($eventos) > 0): ?>

<section class="eventos">

<?php foreach ($eventos as $indice => $evento): ?>

<article class="evento">

<?php

if ($indice == 0) {
    $imagem = "luan1.png";
} elseif ($indice == 1) {
    $imagem = "jonathan.png";
} else {
    $imagem = "maiara.png";
}

?>

<img
    src="<?= htmlspecialchars($imagem) ?>"
    alt="Imagem do evento <?= htmlspecialchars($evento["nome"]) ?>"
    class="imagem-evento"
>

<div class="conteudo-evento">

<h3>
    <?= htmlspecialchars($evento["nome"]) ?>
</h3>

<p>
    <?= htmlspecialchars($evento["descricao"]) ?>
</p>

<p>
    <strong>📅 Data:</strong>
    <?= date(
        "d/m/Y",
        strtotime($evento["data_evento"])
    ) ?>
</p>

<p>
    <strong>🕐 Horário:</strong>
    <?= htmlspecialchars($evento["horario"]) ?>
</p>

<p>
    <strong>📍 Local:</strong>
    <?= htmlspecialchars($evento["local"]) ?>
</p>

<a
    href="evento.php?id=<?= (int)$evento["id"] ?>"
    class="botao"
>
    Ver detalhes
</a>

<a
    href="comprovar-compra.php?evento=<?= urlencode($evento["nome"]) ?>&valor=<?= urlencode($evento["preco"]) ?>"
    class="botao"
>
    Comprar
</a>

</div>

</article>

<?php endforeach; ?>

</section>

<?php else: ?>

<div class="sem-eventos">

🎫

<br><br>

Ainda não existem eventos cadastrados.

<br><br>

<a href="eventos.php" class="botao">
    Ver eventos
</a>

</div>

<?php endif; ?>

<section class="acessibilidade">

<h2>
    ♿ Acessibilidade
</h2>

<p>
    Utilize os recursos abaixo para facilitar sua navegação no site.
</p>

<div class="botoes">

<button
    type="button"
    onclick="aumentarFonte()"
>
    A+ Aumentar fonte
</button>

<button
    type="button"
    onclick="diminuirFonte()"
>
    A− Diminuir fonte
</button>

<button
    type="button"
    onclick="lerPagina()"
>
    🔊 Ouvir página
</button>

</div>

</section>

</main>

<footer>

<p>
    <strong>Acesso Livre</strong>
</p>

<p>
    Eventos acessíveis para todos.
</p>

<p>
    © 2026 Acesso Livre
</p>

</footer>

<div vw class="enabled">

<div vw-access-button class="active"></div>

<div vw-plugin-wrapper>

<div class="vw-plugin-top-wrapper"></div>

</div>

</div>

<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>

<script>

new window.VLibras.Widget(
    "https://vlibras.gov.br/app"
);

let tamanhoFonte = 18;

function aumentarFonte() {

    tamanhoFonte += 2;

    if (tamanhoFonte > 30) {
        tamanhoFonte = 30;
    }

    document.documentElement.style.fontSize =
        tamanhoFonte + "px";
}

function diminuirFonte() {

    tamanhoFonte -= 2;

    if (tamanhoFonte < 14) {
        tamanhoFonte = 14;
    }

    document.documentElement.style.fontSize =
        tamanhoFonte + "px";
}

function lerPagina() {

    if (!("speechSynthesis" in window)) {

        alert(
            "A leitura por voz não está disponível neste navegador."
        );

        return;
    }

    speechSynthesis.cancel();

    const texto =
        document.querySelector("main").innerText;

    const fala =
        new SpeechSynthesisUtterance(texto);

    fala.lang = "pt-BR";

    fala.rate = 0.9;

    speechSynthesis.speak(fala);
}

</script>

</body>

</html>