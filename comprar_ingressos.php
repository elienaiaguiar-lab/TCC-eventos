<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require_once __DIR__ . "/banco/conexao.php";


/* ========================================
   VERIFICAR LOGIN
======================================== */

if (!isset($_SESSION["usuario_id"])) {

    header("Location: login.php");
    exit;

}


/* ========================================
   PEGAR ID DO EVENTO
======================================== */

$id = $_GET["id"] ?? "";

if (!is_numeric($id)) {

    header("Location: eventos.php");
    exit;

}

$id = (int) $id;


/* ========================================
   BUSCAR EVENTO
======================================== */

$sql = $db->prepare("
    SELECT *
    FROM eventos
    WHERE id = ?
    LIMIT 1
");

$sql->execute([$id]);

$evento = $sql->fetch(PDO::FETCH_ASSOC);


/* ========================================
   VERIFICAR EVENTO
======================================== */

if (!$evento) {

    echo "<h2>Evento não encontrado.</h2>";
    echo '<a href="eventos.php">Voltar para eventos</a>';

    exit;
}


/* ========================================
   COMPRAR INGRESSO
======================================== */

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        /* Gera código único */

        $codigo = "AL-" . strtoupper(
            bin2hex(random_bytes(4))
        );


        /* Salva o ingresso */

        $sql = $db->prepare("
            INSERT INTO ingressos
            (
                usuario_id,
                evento_id,
                codigo
            )
            VALUES
            (
                ?,
                ?,
                ?
            )
        ");

        $sql->execute([
            $_SESSION["usuario_id"],
            $id,
            $codigo
        ]);


        /* Vai para a página do ingresso */

        header(
            "Location: ingresso.php?codigo=" .
            urlencode($codigo)
        );

        exit;


    } catch (PDOException $e) {

        $erro = "Erro ao realizar a compra: " .
                $e->getMessage();

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
        Comprar ingresso - Acesso Livre
    </title>


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

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 25px;

        }


        .container {

            width: 100%;

            max-width: 650px;

            background: white;

            border-radius: 25px;

            padding: 40px;

            border: 2px solid #D9EAF5;

            box-shadow:
                0 10px 35px
                rgba(64, 124, 170, 0.15);

        }


        .logo {

            text-align: center;

            color: #195A7A;

            font-size: 32px;

            font-weight: bold;

            margin-bottom: 10px;

        }


        .subtitulo {

            text-align: center;

            color: #7A92A5;

            font-size: 18px;

            margin-bottom: 30px;

        }


        .evento {

            background: #F5FBFF;

            border: 2px solid #D9EAF5;

            border-radius: 18px;

            padding: 25px;

            margin-bottom: 25px;

        }


        .evento h1 {

            color: #195A7A;

            font-size: 30px;

            margin-top: 0;

            margin-bottom: 20px;

        }


        .informacao {

            margin: 14px 0;

            font-size: 19px;

        }


        .informacao strong {

            color: #195A7A;

        }


        .aviso {

            background: #EAF7FF;

            border: 2px solid #B9E1F7;

            border-radius: 15px;

            padding: 20px;

            margin-bottom: 25px;

            font-size: 18px;

            line-height: 1.5;

        }


        .erro {

            background: #FFE8E8;

            border: 2px solid #E29A9A;

            color: #8A2020;

            padding: 15px;

            border-radius: 12px;

            margin-bottom: 20px;

            font-size: 17px;

        }


        .botao {

            width: 100%;

            padding: 18px;

            border: none;

            border-radius: 14px;

            background: #8CCCF6;

            color: #173042;

            font-size: 21px;

            font-weight: bold;

            cursor: pointer;

        }


        .botao:hover {

            background: #73BEEB;

        }


        .voltar {

            display: block;

            text-align: center;

            margin-top: 20px;

            color: #195A7A;

            font-size: 18px;

            font-weight: bold;

            text-decoration: none;

        }


        .acessibilidade {

            margin-top: 30px;

            padding-top: 25px;

            border-top: 2px solid #D9EAF5;

            text-align: center;

        }


        .acessibilidade h2 {

            color: #195A7A;

            font-size: 24px;

        }


        .acessibilidade button {

            padding: 12px 15px;

            margin: 5px;

            border: 2px solid #8CCCF6;

            background: white;

            border-radius: 10px;

            color: #195A7A;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

        }


        @media (max-width: 600px) {

            .container {

                padding: 25px 20px;

            }

            .logo {

                font-size: 27px;

            }

            .evento h1 {

                font-size: 26px;

            }

        }

    </style>

</head>


<body>


<main class="container">


    <div class="logo">

        Acesso Livre

    </div>


    <div class="subtitulo">

        Comprar ingresso

    </div>


    <?php if ($erro): ?>

        <div class="erro">

            <?= htmlspecialchars($erro) ?>

        </div>

    <?php endif; ?>


    <section class="evento">


        <h1>

            <?= htmlspecialchars(
                $evento["nome"]
            ) ?>

        </h1>


        <div class="informacao">

            📅

            <strong>
                Data:
            </strong>

            <?= date(
                "d/m/Y",
                strtotime($evento["data_evento"])
            ) ?>

        </div>


        <div class="informacao">

            🕐

            <strong>
                Horário:
            </strong>

            <?= htmlspecialchars(
                $evento["horario"]
            ) ?>

        </div>


        <div class="informacao">

            📍

            <strong>
                Local:
            </strong>

            <?= htmlspecialchars(
                $evento["local"]
            ) ?>

        </div>


        <div class="informacao">

            🎫

            <strong>
                Valor:
            </strong>

            R$

            <?= number_format(
                (float) $evento["preco"],
                2,
                ",",
                "."
            ) ?>

        </div>


    </section>


    <div class="aviso">

        <strong>
            🎫 Confirme sua compra
        </strong>

        <br><br>

        Você está prestes a comprar um ingresso
        para este evento.

        <br><br>

        Após confirmar, seu ingresso digital
        será gerado automaticamente.

    </div>


    <form method="POST">

        <button
            type="submit"
            class="botao"
        >

            🎫 Confirmar compra

        </button>

    </form>


    <a
        href="evento.php?id=<?= $id ?>"
        class="voltar"
    >

        ← Voltar para o evento

    </a>


    <section class="acessibilidade">


        <h2>
            ♿ Acessibilidade
        </h2>


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


    </section>


</main>


<!-- VLibras -->

<div vw class="enabled">

    <div
        vw-access-button
        class="active"
    ></div>

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