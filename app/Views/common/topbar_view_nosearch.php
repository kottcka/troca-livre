
<!-- Hamburger Icon for Mobile View -->
<div id="hamburger-icon" style="display: none;">
    <div style="width: 25px; height: 3px; background: #000; margin: 4px 0;"></div>
    <div style="width: 25px; height: 3px; background: #000; margin: 4px 0;"></div>
    <div style="width: 25px; height: 3px; background: #000; margin: 4px 0;"></div>
</div>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/default_nosearch.css'); ?>">
</head>
<body>
<div class="top-bar center">
         <div class="logo-container">
            <img src="/assets/images/logo_home.png" alt="Logo do Site" class="logo">
         </div>
         <div class="user-info">
            <?php 
               if(session()->has('user')):
               $names = explode(' ', session()->get('user')->full_name);
               $firstName = $names[0] ?? '';
               $secondName = $names[1] ?? '';
               $twoNames = trim($firstName . ' ' . $secondName);
               ?>
            <span class="user-greeting">Olá, <?= $twoNames; ?></span> <!-- Aqui foi adicionada a classe -->
            <?php endif; ?>
            <a href="<?php echo url_to('login.destroy') ?>">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill-opacity=".8" viewBox="0 0 24 24">
                  <path d="M8.345 4.155h-3.75c-.415 0-.75.336-.75.75v15c0 .415.335.75.75.75h3.75v1.5h-3.75c-1.243 0-2.25-1.007-2.25-2.25v-15c0-1.242 1.007-2.25 2.25-2.25h3.75v1.5zm12.81 8.25l-7.06 7.061-1.061-1.06 5.25-5.25H6.843v-1.5h11.441l-5.251-5.25 1.06-1.061 7.061 7.06z"></path>
               </svg>
               Sair
            </a>
</body>
</html>
