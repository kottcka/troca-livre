<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha</title>
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

        .logo {
            width: 150px;
            display: block;
            margin: 0 auto 20px;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
        }

        .reset-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: white;
            background-color: #7B96D4;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .warning {
            font-size: 14px;
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php 
               if(session()->has('user')):
               $names = explode(' ', session()->get('user')->full_name);
               $firstName = $names[0] ?? '';
               $secondName = $names[1] ?? '';
               $twoNames = trim($firstName . ' ' . $secondName);
               ?>
    <div class="email-container">
        <img src="https://i.imgur.com/rdc6ALC.png" alt="Logo" class="logo">
        <p class="message">
            Olá <?= $twoNames; ?>, recebemos um pedido para redefinir sua senha. Se você solicitou isso, clique no link abaixo:
        </p>
        <?php endif; ?>
        <a href="<?php echo site_url("reset/{$token}"); ?>" class="reset-link">Redefinir Senha</a>
        <p class="warning">
            Atenção: Este link expira em 60 minutos.
        </p>
    </div>
</body>
</html>
