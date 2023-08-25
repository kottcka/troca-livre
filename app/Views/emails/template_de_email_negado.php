<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação Negada</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
        }

        .email-container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
        }

        .warning {
            font-size: 14px;
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1 style="color: #444; font-size: 24px;">Sua solicitação foi negada</h1>
        <p class="message">
    Olá <?= isset($solicitante_name) ? $solicitante_name : 'Nome do Solicitante Desconhecido' ?>,
</p>
<p class="message">
    Lamentamos informar que sua solicitação para o item "<?= isset($itemSolicitado->nome) ? $itemSolicitado->nome : 'Nome do Item Desconhecido' ?>" foi negada pelo proprietário.
</p>
        <p class="message">
            Se você tiver alguma dúvida, entre no anúncio e faça sua pergunta ao proprietário do item.
        </p>
        <p class="message">
            Obrigado por usar nossa plataforma!
        </p>
        <p>Atenciosamente,</p>
        <p>Equipe do Sistema de Trocas e Doações</p>
    </div>
</body>
</html>
