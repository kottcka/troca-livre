<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação de Doação Aceita</title>
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
        <h1 style="color: #444; font-size: 24px;">Solicitação de Doação Aceita</h1>
        <p class="message">
            Olá <?= isset($solicitante->full_name) ? $solicitante->full_name : 'Nome do Solicitante Desconhecido' ?>,
        </p>
        <p class="message">
            Sua solicitação de doação para o item "<?= isset($itemSolicitado->nome) ? $itemSolicitado->nome : 'Nome do Item Desconhecido' ?>" foi aceita por <?= isset($destinatario->full_name) ? $destinatario->full_name : 'Nome do Destinatário Desconhecido' ?>.
        </p>
        <p class="info">
            <strong>Item Solicitado:</strong> <?= isset($itemSolicitado->nome) ? $itemSolicitado->nome : 'Nome do Item Desconhecido' ?><br>
            <strong>Categoria Solicitada:</strong> <?= isset($categoriaSolicitada) ? $categoriaSolicitada : 'Categoria Desconhecida' ?><br>
            <strong>Descrição Solicitada:</strong> <?= isset($itemSolicitado->descricao) ? $itemSolicitado->descricao : 'Descrição Desconhecida' ?>
        </p>
        <p class="message">
            Agora você pode entrar em contato com <?= isset($destinatario->full_name) ? $destinatario->full_name : 'Nome do Destinatário Desconhecido' ?> para organizar os detalhes da doação. Acesse sua conta e vá para a página de solicitações finalizadas.
        </p>
        <p>Atenciosamente,</p>
        <p>Equipe do Sistema de Trocas e Doações</p>
    </div>
</body>
</html>
