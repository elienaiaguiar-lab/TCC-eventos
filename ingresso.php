<?php

session_start();

require_once __DIR__ . "/banco/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if (empty($email) || empty($senha)) {

        $erro = "Preencha todos os campos.";

    } else {

        try {

            $sql = $db->prepare(
                "SELECT * FROM usuarios WHERE email = ?"
            );

            $sql->execute([$email]);

            $usuario = $sql->fetch();

            if ($usuario && password_verify($senha, $usuario["senha"])) {

                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nome"] = $usuario["nome"];

                header("Location: home.php");
                exit;

            } else {

                $erro = "E-mail ou senha incorretos.";

            }

        } catch (PDOException $e) {

            $erro = "Erro ao acessar o banco de dados.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - Acesso Livre</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    font-family: Arial, sans-serif;

    background: linear-gradient(
        135deg,
        #F9FCFF,
        #EAF6FF,
        #F5FBFF
    );

    color: #334E68;

    display: flex;
    justify-content: center;
    align-items: center;

    padding: 20px;
}

.container {
    width: 100%;
    max-width: 430px;

    background: white;

    padding: 40px;

    border-radius: 24px;

    box-shadow:
        0 10px 35px
        rgba(64, 124, 170, 0.15);
}

.logo {
    text-align: center;
    margin-bottom: 25px;
}

.logo img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    display: block;
    margin: 0 auto 15px auto;
}

.logo p {
    margin-top: 8px;
    font-size: 18px;
    color: #7A92A5;
}

h2 {
    font-size: 30px;
    margin-bottom: 8px;
    color: #334E68;
}

.subtitulo {
    font-size: 18px;
    margin-bottom: 25px;
    color: #7A92A5;
}

label {
    display: block;
    font-size: 19px;
    font-weight: bold;
    margin-bottom: 8px;
    margin-top: 18px;
}

input {
    width: 100%;
    padding: 16px;

    border: 2px solid #D9EAF5;
    border-radius: 12px;

    background: #F8FBFD;
    color: #334E68;

    font-size: 18px;
    outline: none;
}

input:focus {
    border-color: #8CCCF6;
    background: white;
}

.esqueci {
    display: block;
    text-align: right;
    margin-top: 10px;

    font-size: 17px;
    color: #195A7A;
    font-weight: bold;
    text-decoration: none;
}

.botao {
    width: 100%;

    margin-top: 25px;
    padding: 16px;

    border: none;
    border-radius: 14px;

    background: #8CCCF6;
    color: #173042;

    font-size: 20px;
    font-weight: bold;

    cursor: pointer;
}

.botao:hover {
    background: #73BEEB;
}

.cadastro {
    text-align: center;
    margin-top: 25px;
    font-size: 17px;
}

.cadastro a {
    color: #195A7A;
    font-weight: bold;
    text-decoration: none;
}

.erro {
    background: #FFE8E8;

    border: 1px solid #E29A9A;

    color: #8A2020;

    padding: 12px;

    border-radius: 10px;

    font-size: 17px;

    margin-bottom: 15px;

    text-align: center;
}

.acessibilidade {
    position: fixed;

    bottom: 15px;
    left: 15px;

    display: flex;
    gap: 8px;

    z-index: 1000;
}

.acessibilidade button {
    width: 50px;
    height: 50px;

    border-radius: 12px;

    border: 2px solid #195A7A;

    background: white;
    color: #195A7A;

    font-size: 18px;
    font-weight: bold;

    cursor: pointer;
}

@media (max-width: 500px) {

    .container {
        padding: 28px 22px;
    }

    .logo img {
        width: 130px;
        height: 130px;
    }

    h2 {
        font-size: 26px;
    }

}

</style>

</head>

<body>

<main class="container">

<div class="logo">

<img
    src="LOGO.png"
    alt="Logo Acesso Livre"
>

<p>
    Eventos acessíveis para todos
</p>

</div>

<h2>Entrar</h2>

<p class="subtitulo">
    Acesse sua conta para continuar.
</p>

<?php if ($erro): ?>

<div class="erro">
    <?= htmlspecialchars($erro) ?>
</div>

<?php endif; ?>

<form method="POST">

<label for="email">
    E-mail
</label>

<input
    type="email"
    id="email"
    name="email"
    placeholder="Digite seu e-mail"
    autocomplete="email"
    required
>

<label for="senha">
    Senha
</label>

<input
    type="password"
    id="senha"
    name="senha"
    placeholder="Digite sua senha"
    autocomplete="current-password"
    required
>

<a
    href="esqueciminhasenha.php"
    class="esqueci"
>
    Esqueci minha senha
</a>

<button
    type="submit"
    class="botao"
>
    Entrar
</button>

</form>

<div class="cadastro">

Ainda não possui uma conta?

<a href="cadastro.php">
    Criar conta
</a>

</div>

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