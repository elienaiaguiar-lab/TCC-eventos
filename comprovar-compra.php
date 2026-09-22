<?php

$evento = $_GET["evento"] ?? "Evento Acesso Livre";
$valor = $_GET["valor"] ?? "0";

$valorNumerico = (float) $valor;
$valorFormatado = number_format($valorNumerico, 2, ",", ".");

$codigoPix = "00020126580014BR.GOV.BCB.PIX0136acesso.livre@pix.com520400005303986540" .
    number_format($valorNumerico, 2, ".", "") .
    "5802BR5913ACESSO LIVRE6009SAO PAULO62070503***6304ABCD";

$qrCode = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($codigoPix);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar compra</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5fbff;
            color: #334e68;
        }

        .pagina {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .caixa {
            width: 100%;
            max-width: 500px;
            background: white;
            border: 1px solid #d9eaf5;
            border-radius: 14px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(64, 124, 170, 0.12);
        }

        h1 {
            margin-bottom: 10px;
        }

        .evento {
            color: #7a92a5;
        }

        .valor {
            font-size: 26px;
            font-weight: bold;
            color: #73aeda;
            margin: 20px 0;
        }

        .linha {
            height: 1px;
            background: #d9eaf5;
            margin-bottom: 25px;
        }

        .qr {
            width: 230px;
            height: 230px;
            margin: 10px auto 20px;
            display: block;
        }

        .texto {
            color: #7a92a5;
            font-size: 14px;
        }

        .codigo {
            width: 100%;
            min-height: 90px;
            padding: 12px;
            resize: none;
            border: 1px solid #d9eaf5;
            border-radius: 8px;
            background: #f8fbfd;
            color: #334e68;
            font-size: 12px;
        }

        button {
            width: 100%;
            margin-top: 12px;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #8cccf6;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #73beeb;
        }

        .confirmar {
            background: #5ca6d6;
        }

        .confirmar:hover {
            background: #438fbe;
        }

        #mensagem {
            display: none;
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #e8f6ff;
        }
    </style>
</head>

<body>

    <div class="pagina">

        <div class="caixa">

            <h1>Confirmar compra</h1>

            <p class="evento">
                <?= htmlspecialchars($evento) ?>
            </p>

            <div class="valor">
                R$ <?= $valorFormatado ?>
            </div>

            <div class="linha"></div>

            <h2>Pagamento via Pix</h2>

            <img
                src="<?= htmlspecialchars($qrCode) ?>"
                alt="QR Code Pix"
                class="qr"
            >

            <p class="texto">
                Escaneie o QR Code ou copie o código abaixo.
            </p>

            <textarea id="codigoPix" class="codigo" readonly><?= htmlspecialchars($codigoPix) ?></textarea>

            <button type="button" onclick="copiarPix()">
                Copiar código Pix
            </button>

            <button type="button" class="confirmar" onclick="confirmarCompra()">
                Confirmar compra
            </button>

            <div id="mensagem">
                Compra confirmada!
            </div>

        </div>

    </div>

    <script>
        function copiarPix() {
            const campo = document.getElementById("codigoPix");

            campo.select();
            campo.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(campo.value);

            alert("Código Pix copiado!");
        }

        function confirmarCompra() {
            document.getElementById("mensagem").style.display = "block";
        }
    </script>

</body>

</html>