<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/login_style.css'); ?>">
    <script>
        function validatePasswords() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("password_confirm").value;

            if (password !== confirmPassword) {
                alert("As senhas não são iguais!");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <div class="background-image"></div>
    <div class="login-container">
        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" class="logo">
        <form action="<?php echo url_to('forgot.update', $token); ?>" method="post" onsubmit="return validatePasswords();">
        <?php echo csrf_field(); ?>
            <label for="password">Nova Senha</label>
            <input type="password" id="password" name="password">
            <span class="error"><?php echo session()->getFlashdata('errors')['password'] ?? ''; ?></span>

            <label for="password_confirm">Confirme a Nova Senha</label>
            <input type="password" id="password_confirm" name="password_confirm">
            <span class="error"><?php echo session()->getFlashdata('errors')['password_confirm'] ?? ''; ?></span>

            <button type="submit" class="login-button">Redefinir senha</button>
            <a href="/login" class="forgot-password">Voltar ao login</a>
        </form>
    </div>
</body>

</html>
