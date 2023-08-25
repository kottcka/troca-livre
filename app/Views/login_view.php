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
</head>
<body>
    <div class="background-image"></div>
    <div class="login-container">
        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" class="logo">

        <!-- Mensagem de erro centralizada -->
        <?php if(session()->has('error')): ?>
            <div class="error-container">
                <span class="text text-danger"> <?php echo session()->getFlashdata('error'); ?> </span>
            </div>
        <?php endif; ?>

        <form action="<?php echo url_to('login.store')?>" method="post">
        <?php echo csrf_field(); ?>
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
            <span class="text text-danger"><?php echo session()->getFlashdata('errors')['email'] ?? '' ?></span>

            <label for="password">Senha</label>
            <input type="password" id="password" name="password">
            <span class="text text-danger"><?php echo session()->getFlashdata('errors')['password'] ?? '' ?></span>

            <a href="forgot/password" class="forgot-password">Esqueceu a sua senha?</a>
            <button type="submit" class="login-button">Login</button>       
            <a href="register" class="create-account">Criar conta</a>    
        </form>
    </div>
</body>
</html>
