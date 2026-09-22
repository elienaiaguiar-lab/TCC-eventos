<?php

session_start();

require_once __DIR__ . "/banco/conexao.php";

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmar_senha = $_POST["confirmar_senha"] ?? "";

    // Verifica se todos os campos foram preenchidos
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {

        $erro = "Preencha todos os campos.";

    // Verifica se o e-mail é válido
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erro = "Digite um e-mail válido.";

    // Verifica o tamanho da senha
    } elseif (strlen($senha) < 6) {

        $erro = "A senha precisa ter pelo menos 6 caracteres.";

    // Verifica se as senhas são iguais
    } elseif ($senha !== $confirmar_senha) {

        $erro = "As senhas não são iguais.";

    } else {

        // Verifica se o e-mail já existe
        $verificar = $db->prepare(
            "SELECT id FROM usuarios WHERE email = ?"
        );

        $verificar->execute([$email]);

        if ($verificar->fetch()) {

            $erro = "Este e-mail já está cadastrado.";

        } else {

            // Criptografa a senha antes de salvar
            $senha_segura = password_hash(
                $senha,
                PASSWORD_DEFAULT
            );

            // Salva o usuário no banco
            $sql = $db->prepare("
                INSERT INTO usuarios
                (nome, email, senha)
                VALUES (?, ?, ?)
            ");

            $sql->execute([
                $nome,
                $email,
                $senha_segura
            ]);

            $sucesso = "Cadastro realizado com sucesso!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Criar conta - Acesso Livre</title>

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
            max-width: 500px;

            background: white;

            padding: 40px;

            border-radius: 24px;

            box-shadow:
                0 10px 35px
                rgba(64, 124, 170, 0.15);
        }

        .voltar {
            display: block;

            margin-bottom: 20px;

            color: #195A7A;

            text-decoration: none;

            font-size: 17px;

            font-weight: bold;
        }

        .logo {
            text-align: center;

            margin-bottom: 25px;
        }

        .logo h1 {
            margin: 0;

            font-size: 36px;

            color: #195A7A;
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

            margin-top: 18px;

            margin-bottom: 8px;
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

        .botao {
            width: 100%;

            margin-top: 28px;

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

        .login {
            text-align: center;

            margin-top: 25px;

            font-size: 17px;
        }

        .login a {
            color: #195A7A;

            font-weight: bold;

            text-decoration: none;
        }

        .erro {
            background: #FFE8E8;

            border: 1px solid #E29A9A;

            color: #8A2020;

            padding: 13px;

            border-radius: 10px;

            font-size: 17px;

            margin-bottom: 15px;

            text-align: center;
        }

        .sucesso {
            background: #E7FAED;

            border: 1px solid #79B98A;

            color: #216B35;

            padding: 13px;

            border-radius: 10px;

            font-size: 17px;

            margin-bottom: 15px;

            text-align: center;
        }

        /* ACESSIBILIDADE */

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

        @media (max-width: 550px) {

            .container {
                padding: 28px 22px;
            }

            .logo h1 {
                font-size: 30px;
            }

            h2 {
                font-size: 26px;
            }

        }

    </style>

</head>

<body>

    <main class="container">

        <a
            href="index.php"
            class="voltar"
        >
            ← Voltar
        </a>


        <div class="logo">

            <h1>Acesso Livre</h1>

            <p>
                Eventos acessíveis para todos
            </p>

        </div>


        <h2>Criar conta</h2>

        <p class="subtitulo">
            Preencha seus dados para criar sua conta.
        </p>


        <?php if ($erro): ?>

            <div class="erro">
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>


        <?php if ($sucesso): ?>

            <div class="sucesso">
                <?= htmlspecialchars($sucesso) ?>
            </div>

            <a
                href="login.php"
                class="botao"
                style="text-decoration: none; text-align: center;"
            >
                Ir para o login
            </a>

        <?php else: ?>


            <form method="POST">

                <label for="nome">
                    Nome completo
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Digite seu nome completo"
                    autocomplete="name"
                    required
                >


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
                    minlength="6"
                    autocomplete="new-password"
                    required
                >


                <label for="confirmar_senha">
                    Confirmar senha
                </label>

                <input
                    type="password"
                    id="confirmar_senha"
                    name="confirmar_senha"
                    placeholder="Digite a senha novamente"
                    minlength="6"
                    autocomplete="new-password"
                    required
                >


                <button
                    type="submit"
                    class="botao"
                >
                    Criar minha conta
                </button>

            </form>


            <div class="login">

                Já possui uma conta?

                <a href="login.php">
                    Entrar
                </a>

            </div>

        <?php endif; ?>

    </main>


    <!-- BOTÕES DE ACESSIBILIDADE -->

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


    <!-- VLibras -->

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