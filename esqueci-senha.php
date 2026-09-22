<?php

session_start();

try {

    $db = new PDO(
        "sqlite:" . __DIR__ . "/banco/acesso_livre.db"
    );

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

    die(
        "Erro ao acessar o banco de dados: "
        . $e->getMessage()
    );

}

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");

    if (empty($email)) {

        $erro = "Digite seu e-mail.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erro = "Digite um e-mail válido.";

    } else {

        $sql = $db->prepare(
            "SELECT id FROM usuarios WHERE email = ?"
        );

        $sql->execute([$email]);

        $usuario = $sql->fetch();

        if ($usuario) {

            $_SESSION["recuperar_usuario_id"] =
                $usuario["id"];

            header("Location: nova-senha.php");

            exit;

        } else {

            $erro =
                "Não encontramos uma conta com esse e-mail.";
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

    <title>
        Esqueci minha senha - Acesso Livre
    </title>

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

            font-size: 18px;

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

        .icone {

            width: 75px;

            height: 75px;

            margin: 0 auto 20px;

            border-radius: 50%;

            background: #EAF7FF;

            display: flex;

            justify-content: center;

            align-items: center;

            font-size: 38px;
        }

        h2 {

            font-size: 30px;

            margin-bottom: 10px;

            color: #334E68;
        }

        .subtitulo {

            font-size: 18px;

            line-height: 1.5;

            color: #7A92A5;

            margin-bottom: 25px;
        }

        label {

            display: block;

            font-size: 19px;

            font-weight: bold;

            margin-bottom: 8px;
        }

        input {

            width: 100%;

            padding: 17px;

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

            margin-top: 25px;

            padding: 17px;

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

        .erro {

            background: #FFE8E8;

            border: 1px solid #E29A9A;

            color: #8A2020;

            padding: 13px;

            border-radius: 10px;

            font-size: 17px;

            margin-bottom: 18px;

            text-align: center;
        }

        .sucesso {

            background: #E7FAED;

            border: 1px solid #79B98A;

            color: #216B35;

            padding: 13px;

            border-radius: 10px;

            font-size: 17px;

            margin-bottom: 18px;

            text-align: center;
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
            href="login.php"
            class="voltar"
        >
            ← Voltar para o login
        </a>

        <div class="logo">

            <div class="icone">
                🔑
            </div>

            <h1>
                Acesso Livre
            </h1>

            <p>
                Eventos acessíveis para todos
            </p>

        </div>

        <h2>
            Esqueci minha senha
        </h2>

        <p class="subtitulo">

            Não se preocupe! Digite o
            e-mail cadastrado na sua conta
            para criar uma nova senha.

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

            <button

                type="submit"

                class="botao"

            >

                Continuar

            </button>

        </form>

        <div class="login">

            Lembrou sua senha?

            <a href="login.php">

                Voltar para o login

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