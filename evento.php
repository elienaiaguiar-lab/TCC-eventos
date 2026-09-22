<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . "/banco/conexao.php";

$erro = "";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: home.php");
    exit;
}

$sql = $db->prepare("
    SELECT *
    FROM eventos
    WHERE id = ?
");

$sql->execute([(int)$id]);

$evento = $sql->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    header("Location: home.php");
    exit;
}

if ((int)$evento["id"] === 1) {
    $imagemEvento = "luan1.png";
} elseif ((int)$evento["id"] === 2) {
    $imagemEvento = "jonathan.png";
} else {
    $imagemEvento = "maiara.png";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($evento["nome"]) ?> - Acesso Livre</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(
        135deg,
        #F9FCFF,
        #EAF6FF,
        #F5FBFF
    );
    color: #334E68;
    line-height: 1.5;
}

header {
    background: white;
    border-bottom: 2px solid #D9EAF5;
    padding: 18px 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.logo {
    color: #195A7A;
    font-size: 30px;
    font-weight: bold;
    text-decoration: none;
}

nav {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

nav a {
    color: #334E68;
    text-decoration: none;
    font-size: 17px;
    font-weight: bold;
    padding: 10px 14px;
    border-radius: 10px;
}

nav a:hover {
    background: #EAF6FF;
    color: #195A7A;
}

.sair {
    background: #8CCCF6;
    color: #173042 !important;
}

.sair:hover {
    background: #73BEEB !important;
}

main {
    max-width: 1100px;
    margin: auto;
    padding: 45px 5%;
}

.voltar {
    display: inline-block;
    margin-bottom: 25px;
    color: #195A7A;
    text-decoration: none;
    font-size: 19px;
    font-weight: bold;
}

.voltar:hover {
    text-decoration: underline;
}

.evento-container {
    background: white;
    border: 2px solid #D9EAF5;
    border-radius: 25px;
    overflow: hidden;
    box-shadow:
        0 10px 35px
        rgba(64, 124, 170, 0.12);
}

.imagem-evento {
    width: 100%;
    height: 380px;
    object-fit: cover;
    display: block;
    background: #EAF6FF;
}

.informacoes {
    padding: 40px;
}

.informacoes h1 {
    color: #195A7A;
    font-size: 40px;
    margin-top: 0;
    margin-bottom: 20px;
}

.descricao {
    font-size: 20px;
    color: #526B7A;
    margin-bottom: 30px;
}

.dados {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 30px;
}

.dado {
    background: #F5FBFF;
    border: 2px solid #D9EAF5;
    border-radius: 15px;
    padding: 20px;
}

.dado strong {
    display: block;
    color: #195A7A;
    font-size: 18px;
    margin-bottom: 5px;
}

.dado span {
    font-size: 19px;
}

.acessibilidade-evento {
    background: #EAF7FF;
    border: 2px solid #B9E1F7;
    border-radius: 18px;
    padding: 25px;
    margin-top: 25px;
}

.acessibilidade-evento h2 {
    color: #195A7A;
    font-size: 28px;
    margin-top: 0;
}

.acessibilidade-evento p {
    font-size: 18px;
    margin: 12px 0;
}

.form-compra {
    margin-top: 30px;
}

.botao {
    display: block;
    width: 100%;
    padding: 18px;
    background: #8CCCF6;
    color: #173042;
    text-align: center;
    border: none;
    border-radius: 15px;
    font-size: 21px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
}

.botao:hover {
    background: #73BEEB;
}

.ferramentas {
    margin-top: 40px;
    background: white;
    border: 2px solid #D9EAF5;
    border-radius: 20px;
    padding: 30px;
}

.ferramentas h2 {
    color: #195A7A;
    font-size: 28px;
    margin-top: 0;
}

.botoes {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 20px;
}

.botoes button {
    padding: 14px 18px;
    background: white;
    border: 2px solid #8CCCF6;
    border-radius: 12px;
    color: #195A7A;
    font-size: 17px;
    font-weight: bold;
    cursor: pointer;
}

.botoes button:hover {
    background: #EAF7FF;
}

footer {
    margin-top: 30px;
    padding: 35px;
    background: #195A7A;
    color: white;
    text-align: center;
}

footer p {
    margin: 6px;
    font-size: 17px;
}

@media (max-width: 700px) {

    header {
        flex-direction: column;
    }

    main {
        padding: 30px 20px;
    }

    .imagem-evento {
        height: 250px;
    }

    .informacoes {
        padding: 25px;
    }

    .informacoes h1 {
        font-size: 32px;
    }

    .dados {
        grid-template-columns: 1fr;
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

<a href="home.php">
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

<a href="home.php" class="voltar">
    ← Voltar para eventos
</a>

<article class="evento-container">

<img
    src="<?= htmlspecialchars($imagemEvento) ?>"
    alt="Imagem do evento <?= htmlspecialchars($evento["nome"]) ?>"
    class="imagem-evento"
>

<div class="informacoes">

<h1>
    <?= htmlspecialchars($evento["nome"]) ?>
</h1>

<p class="descricao">
    <?= htmlspecialchars($evento["descricao"]) ?>
</p>

<div class="dados">

<div class="dado">

<strong>
    📅 Data
</strong>

<span>
    <?= date(
        "d/m/Y",
        strtotime($evento["data_evento"])
    ) ?>
</span>

</div>

<div class="dado">

<strong>
    🕐 Horário
</strong>

<span>
    <?= htmlspecialchars($evento["horario"]) ?>
</span>

</div>

<div class="dado">

<strong>
    📍 Local
</strong>

<span>
    <?= htmlspecialchars($evento["local"]) ?>
</span>

</div>

<div class="dado">

<strong>
    🎫 Valor
</strong>

<span>
    R$ <?= number_format(
        $evento["preco"],
        2,
        ",",
        "."
    ) ?>
</span>

</div>

</div>

<section class="acessibilidade-evento">

<h2>
    ♿ Acessibilidade
</h2>

<p>
<strong>Recursos disponíveis:</strong>
<?= htmlspecialchars($evento["acessibilidade"] ?? "Não informado") ?>
</p>

<p>
<strong>🔊 Audiodescrição:</strong>
<?= htmlspecialchars($evento["audiodescricao"] ?? "Não informado") ?>
</p>

<p>
<strong>🗺️ Mapa sensorial:</strong>
<?= htmlspecialchars($evento["mapa_sensorial"] ?? "Não informado") ?>
</p>

</section>

<div class="form-compra">

<a
    href="comprar_ingressos.php?id=<?= (int)$evento["id"] ?>"
    class="botao"
>
    🎫 Comprar ingresso
</a>

</div>

</div>

</article>

<section class="ferramentas">

<h2>
    ♿ Recursos de acessibilidade
</h2>

<p>
    Utilize os recursos abaixo para facilitar sua navegação.
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