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
    <style>
        .loading-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 1000;
            text-align: center;
            padding-top: 50%;
        }

        .loading-overlay span {
            font-size: 1.5em;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="background-image"></div>
    <div class="login-container">
        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" class="logo">

        <?php if(session()->has('error')): ?>
            <span class="text-danger"><?php echo session()->get('error'); ?></span>
        <?php endif; ?>

        <?php if(session()->has('forgot_sent')): ?>
            <span class="success"><?php echo session()->get('forgot_sent'); ?></span>
        <?php endif; ?>

        <?php if(session()->has('forgot_not_sent')): ?>
            <span class="text-danger"><?php echo session()->get('forgot_not_sent'); ?></span>
        <?php endif; ?>

        <?php if(session()->has('token_not_found')): ?>
            <span class="text-danger"><?php echo session()->get('token_not_found'); ?></span>
        <?php endif; ?>

        <?php if(session()->has('updated')): ?>
            <span class="success"><?php echo session()->get('updated'); ?></span>
        <?php endif; ?>

        <?php if(session()->has('not_updated')): ?>
            <span class="text-danger"><?php echo session()->get('not_updated'); ?></span>
        <?php endif; ?>

        <form action="<?php echo url_to('forgot.store'); ?>" method="post">
            <?php echo csrf_field(); ?>
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
            <span class="error"><?php echo session()->getFlashdata('errors')['email'] ?? ''; ?></span>

            <div class="loading-overlay">
                <span>Enviando...</span>
            </div>
            <button type="submit" class="login-button">Solicitar redefinição de senha</button>
            <a href="/login" class="forgot-password">Voltar ao login</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(event) {
                const loadingOverlay = form.querySelector('.loading-overlay');
                loadingOverlay.style.display = 'block';
            });
        });
    </script>
</body>
</html>
