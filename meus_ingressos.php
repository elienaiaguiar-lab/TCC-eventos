<?php

session_start();

require_once __DIR__ . "/banco/conexao.php";


/* Verificar login */

if (!isset($_SESSION["usuario_id"])) {

    header("Location: login.php");
    exit;

}


$usuario_id = $_SESSION["usuario_id"];


/* Buscar ingressos do usuário */

$sql = $db->prepare("
    SELECT
        ingressos.codigo,
        eventos.nome,
        eventos.data_evento,
        eventos.horario,
        eventos.local,
        eventos.preco
    FROM ingressos
    INNER JOIN eventos
        ON ingressos.evento_id = eventos.id
    WHERE ingressos.usuario_id = ?
    ORDER BY eventos.data_evento ASC
");

$sql->execute([$usuario_id]);

$ingressos = $sql->fetchAll();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meus Ingressos - Acesso Livre</title>


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
        }


        header {

            background: white;

            border-bottom: 2px solid #D9EAF5;

            padding: 18px 5%;

            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

            gap: 15px;
        }


        .logo {

            color: #195A7A;

            font-size: 30px;

            font-weight: bold;

            text-decoration: none;
        }


        nav {

            display: flex;

            gap: 8px;

            flex-wrap: wrap;
        }


        nav a {

            color: #334E68;

            text-decoration: none;

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


        main {

            max-width: 1100px;

            margin: auto;

            padding: 45px 5%;
        }


        h1 {

            color: #195A7A;

            font-size: 38px;

            margin-bottom: 10px;
        }


        .subtitulo {

            color: #7A92A5;

            font-size: 19px;

            margin-bottom: 30px;
        }


        .ingressos {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 25px;
        }


        .ingresso {

            background: white;

            border: 2px solid #D9EAF5;

            border-radius: 20px;

            padding: 25px;

            box-shadow:
                0 8px 25px
                rgba(64, 124, 170, 0.10);
        }


        .ingresso h2 {

            color: #195A7A;

            margin-top: 0;

            font-size: 26px;
        }


        .informacao {

            margin: 12px 0;

            font-size: 18px;
        }


        .informacao strong {

            color: #195A7A;
        }


        .codigo {

            margin-top: 20px;

            padding: 15px;

            background: #EAF7FF;

            border: 2px dashed #8CCCF6;

            border-radius: 12px;

            text-align: center;

            font-size: 18px;
        }


        .codigo strong {

            color: #195A7A;
        }


        .botao {

            display: block;

            margin-top: 20px;

            padding: 14px;

            background: #8CCCF6;

            color: #173042;

            text-decoration: none;

            text-align: center;

            border-radius: 12px;

            font-size: 18px;

            font-weight: bold;
        }


        .botao:hover {

            background: #73BEEB;
        }


        .vazio {

            background: white;

            border: 2px solid #D9EAF5;

            border-radius: 20px;

            padding: 40px;

            text-align: center;

            font-size: 20px;
        }


        .vazio p {

            color: #7A92A5;
        }


        footer {

            margin-top: 40px;

            background: #195A7A;

            color: white;

            text-align: center;

            padding: 30px;
        }


        @media (max-width: 700px) {

            header {

                flex-direction: column;

            }


            .ingressos {

                grid-template-columns: 1fr;

            }


            h1 {

                font-size: 30px;

            }

        }

    </style>

</head>


<body>


<header>

    <a
        href="home.php"
        class="logo"
    >
        Acesso Livre
    </a>


    <nav>

        <a href="home.php">
            Início
        </a>

        <a href="eventos.php">
            Eventos
        </a>

        <a href="meus-ingressos.php">
            Meus ingressos
        </a>

        <a href="perfil.php">
            Meu perfil
        </a>

        <a
            href="logout.php"
            class="sair"
        >
            Sair
        </a>

    </nav>

</header>


<main>


    <h1>
        🎫 Meus ingressos
    </h1>


    <p class="subtitulo">

        Aqui estão os ingressos que você comprou.

    </p>


    <?php if (count($ingressos) > 0): ?>


        <div class="ingressos">


            <?php foreach ($ingressos as $ingresso): ?>


                <article class="ingresso">


                    <h2>

                        <?= htmlspecialchars(
                            $ingresso["nome"]
                        ) ?>

                    </h2>


                    <div class="informacao">

                        📅

                        <strong>
                            Data:
                        </strong>

                        <?= date(
                            "d/m/Y",
                            strtotime(
                                $ingresso["data_evento"]
                            )
                        ) ?>

                    </div>


                    <div class="informacao">

                        🕐

                        <strong>
                            Horário:
                        </strong>

                        <?= htmlspecialchars(
                            $ingresso["horario"]
                        ) ?>

                    </div>


                    <div class="informacao">

                        📍

                        <strong>
                            Local:
                        </strong>

                        <?= htmlspecialchars(
                            $ingresso["local"]
                        ) ?>

                    </div>


                    <div class="informacao">

                        🎫

                        <strong>
                            Valor:
                        </strong>

                        R$

                        <?= number_format(
                            $ingresso["preco"],
                            2,
                            ",",
                            "."
                        ) ?>

                    </div>


                    <div class="codigo">

                        <strong>
                            Código do ingresso
                        </strong>

                        <br>

                        <?= htmlspecialchars(
                            $ingresso["codigo"]
                        ) ?>

                    </div>


                </article>


            <?php endforeach; ?>


        </div>


    <?php else: ?>


        <div class="vazio">

            <h2>
                Você ainda não possui ingressos.
            </h2>

            <p>
                Encontre um evento e compre seu primeiro ingresso!
            </p>

            <a
                href="home.php"
                class="botao"
            >
                Ver eventos
            </a>

        </div>


    <?php endif; ?>


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


</body>

</html>