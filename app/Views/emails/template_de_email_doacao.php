<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação de Doação</title>
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

        .info {
            font-size: 14px;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1 style="color: #444; font-size: 24px;">Nova Solicitação de Doação</h1>
        <p class="message">
            Olá <?= $destinatario->full_name ?>,
        </p>
        <p class="message">
            Você recebeu uma nova solicitação de doação do usuário <?= $solicitante->full_name ?> para o item "<?= $itemSolicitado->nome ?>".
        </p>
        <p class="info">
            Categoria: <?= $categoriaSolicitado ?><br>
            Descrição: <?= $itemSolicitado->descricao ?>
        </p>
        <p class="message">
            Para aceitar ou recusar esta solicitação, acesse sua conta e vá para a página de solicitações recebidas.
        </p>
        <p>Atenciosamente,</p>
        <p>Equipe do Sistema de Trocas e Doações</p>
    </div>
</body>
</html>
