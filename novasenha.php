<?php

session_start();

require_once "conexao.php";

if (!isset($_SESSION["recuperar_usuario_id"])) {
    header("Location: esqueci-senha.php");
    exit;
}

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $senha = $_POST["senha"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";

    if (empty($senha) || empty($confirmar)) {

        $erro = "Preencha os dois campos.";

    } elseif (strlen($senha) < 6) {

        $erro = "A senha deve ter pelo menos 6 caracteres.";

    } elseif ($senha !== $confirmar) {

        $erro = "As senhas não são iguais.";

    } else {

        $senhaCriptografada = password_hash(
            $senha,
            PASSWORD_DEFAULT
        );

        $sql = $db->prepare(
            "UPDATE usuarios SET senha = ? WHERE id = ?"
        );

        $sql->execute([
            $senhaCriptografada,
            $_SESSION["recuperar_usuario_id"]
        ]);

        unset($_SESSION["recuperar_usuario_id"]);

        header("Location: login.php?senha=alterada");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Nova senha - Acesso Livre</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    font-family: Arial, sans-serif;
    background: #F5FBFF;
    color: #334E68;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.container {
    width: 100%;
    max-width: 480px;
    background: white;
    border: 1px solid #D9EAF5;
    border-radius: 12px;
    padding: 35px;
}

.logo {
    text-align: center;
    margin-bottom: 25px;
}

.icone {
    width: 65px;
    height: 65px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #EAF6FF;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 32px;
}

.logo h1 {
    margin: 0;
    color: #195A7A;
    font-size: 30px;
}

.logo p {
    margin: 7px 0 0;
    color: #7A92A5;
    font-size: 16px;
}

h2 {
    margin-bottom: 10px;
    color: #334E68;
    font-size: 26px;
}

.subtitulo {
    color: #7A92A5;
    line-height: 1.5;
    margin-bottom: 25px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 14px;
    margin-bottom: 18px;
    border: 1px solid #D9EAF5;
    border-radius: 8px;
    background: #F8FBFD;
    color: #334E68;
    font-size: 16px;
    outline: none;
}

input:focus {
    border-color: #8CCCF6;
    background: white;
}

.botao {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 8px;
    background: #8CCCF6;
    color: #173042;
    font-size: 17px;
    font-weight: bold;
    cursor: pointer;
}

.botao:hover {
    background: #73BEEB;
}

.erro {
    background: #FFE8E8;
    border: 1px solid #E29A9A;
    color: #8A2020;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 18px;
    text-align: center;
}

.voltar {
    display: block;
    margin-top: 20px;
    text-align: center;
    color: #195A7A;
    text-decoration: none;
    font-weight: bold;
}

.acessibilidade {
    position: fixed;
    bottom: 15px;
    left: 15px;
    display: flex;
    gap: 7px;
}

.acessibilidade button {
    width: 45px;
    height: 45px;
    border: 1px solid #195A7A;
    border-radius: 8px;
    background: white;
    color: #195A7A;
    font-weight: bold;
    cursor: pointer;
}

@media (max-width: 550px) {

    .container {
        padding: 25px 20px;
    }

}

</style>

</head>

<body>

<main class="container">

<div class="logo">

    <div class="icone">
        🔐
    </div>

    <h1>
        Acesso Livre
    </h1>

    <p>
        Eventos acessíveis para todos
    </p>

</div>

<h2>
    Nova senha
</h2>

<p class="subtitulo">
    Digite sua nova senha e confirme para finalizar a alteração.
</p>

<?php if ($erro): ?>

<div class="erro">
    <?= htmlspecialchars($erro) ?>
</div>

<?php endif; ?>

<form method="POST">

<label for="senha">
    Nova senha
</label>

<input
    type="password"
    id="senha"
    name="senha"
    placeholder="Digite sua nova senha"
    required
>

<label for="confirmar">
    Confirmar senha
</label>

<input
    type="password"
    id="confirmar"
    name="confirmar"
    placeholder="Digite a senha novamente"
    required
>

<button type="submit" class="botao">
    Alterar senha
</button>

</form>

<a href="login.php" class="voltar">
    ← Voltar para o login
</a>

</main>

<div class="acessibilidade">

<button
    type="button"
    onclick="aumentarFonte()"
    aria-label="Aumentar fonte"
>
    A+
</button>

<button
    type="button"
    onclick="diminuirFonte()"
    aria-label="Diminuir fonte"
>
    A−
</button>

<button
    type="button"
    onclick="lerPagina()"
    aria-label="Ouvir página"
>
    🔊
</button>

</div>

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

    if (tamanhoFonte > 28) {
        tamanhoFonte = 28;
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

    speechSynthesis.speak(fala);
}

</script>

</body>

</html>