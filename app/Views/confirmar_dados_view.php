<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Dados</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/editarperfil_style.css'); ?>">
</head>

<body>
    <div class="background-image"></div>
    <div class="register-container">
        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" class="logo">

        <?php if(session()->has('error')): ?>
            <span class="error"><?php echo session()->getFlashdata('error'); ?></span>
        <?php endif ?>

        <?php if(session()->has('success')): ?>
            <span class="success"><?php echo session()->getFlashdata('success'); ?></span>
        <?php endif ?>

        <form action="<?php echo $action_url; ?>" method="post" id="confirmarDadosForm">
        <?php echo csrf_field(); ?>
            <div class="input-row">
                <div class="input-col">
                    <label for="full_name">Nome Completo</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo $user->full_name; ?>">
                </div>

                <div class="input-col">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo $user->cpf; ?>">
                </div>
            </div>

            <div class="input-row">
                <div class="input-col">
                    <label for="phone">Telefone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $user->phone; ?>">
                </div>

                <div class="input-col">
                    <label for="state">Estado</label>
                    <select name="state" id="state">
    <option value="" <?php echo empty($user->state) ? 'selected' : ''; ?>></option>
    <option value="AC" <?php echo $user->state == 'AC' ? 'selected' : ''; ?>>AC - Acre</option>
    <option value="AL" <?php echo $user->state == 'AL' ? 'selected' : ''; ?>>AL - Alagoas</option>
    <option value="AP" <?php echo $user->state == 'AP' ? 'selected' : ''; ?>>AP - Amapá</option>
    <option value="AM" <?php echo $user->state == 'AM' ? 'selected' : ''; ?>>AM - Amazonas</option>
    <option value="BA" <?php echo $user->state == 'BA' ? 'selected' : ''; ?>>BA - Bahia</option>
    <option value="CE" <?php echo $user->state == 'CE' ? 'selected' : ''; ?>>CE - Ceará</option>
    <option value="DF" <?php echo $user->state == 'DF' ? 'selected' : ''; ?>>DF - Distrito Federal</option>
    <option value="ES" <?php echo $user->state == 'ES' ? 'selected' : ''; ?>>ES - Espírito Santo</option>
    <option value="GO" <?php echo $user->state == 'GO' ? 'selected' : ''; ?>>GO - Goiás</option>
    <option value="MA" <?php echo $user->state == 'MA' ? 'selected' : ''; ?>>MA - Maranhão</option>
    <option value="MT" <?php echo $user->state == 'MT' ? 'selected' : ''; ?>>MT - Mato Grosso</option>
    <option value="MS" <?php echo $user->state == 'MS' ? 'selected' : ''; ?>>MS - Mato Grosso do Sul</option>
    <option value="MG" <?php echo $user->state == 'MG' ? 'selected' : ''; ?>>MG - Minas Gerais</option>
    <option value="PA" <?php echo $user->state == 'PA' ? 'selected' : ''; ?>>PA - Pará</option>
    <option value="PB" <?php echo $user->state == 'PB' ? 'selected' : ''; ?>>PB - Paraíba</option>
    <option value="PR" <?php echo $user->state == 'PR' ? 'selected' : ''; ?>>PR - Paraná</option>
    <option value="PE" <?php echo $user->state == 'PE' ? 'selected' : ''; ?>>PE - Pernambuco</option>
    <option value="PI" <?php echo $user->state == 'PI' ? 'selected' : ''; ?>>PI - Piauí</option>
    <option value="RJ" <?php echo $user->state == 'RJ' ? 'selected' : ''; ?>>RJ - Rio de Janeiro</option>
    <option value="RN" <?php echo $user->state == 'RN' ? 'selected' : ''; ?>>RN - Rio Grande do Norte</option>
    <option value="RS" <?php echo $user->state == 'RS' ? 'selected' : ''; ?>>RS - Rio Grande do Sul</option>
    <option value="RO" <?php echo $user->state == 'RO' ? 'selected' : ''; ?>>RO - Rondônia</option>
    <option value="RR" <?php echo $user->state == 'RR' ? 'selected' : ''; ?>>RR - Roraima</option>
    <option value="SC" <?php echo $user->state == 'SC' ? 'selected' : ''; ?>>SC - Santa Catarina</option>
    <option value="SP" <?php echo $user->state == 'SP' ? 'selected' : ''; ?>>SP - São Paulo</option>
    <option value="SE" <?php echo $user->state == 'SE' ? 'selected' : ''; ?>>SE - Sergipe</option>
    <option value="TO" <?php echo $user->state == 'TO' ? 'selected' : ''; ?>>TO - Tocantins</option>
</select>
                </div>
            </div>                        

            <div class="input-row">
                <div class="input-col">
                    <label for="city">Cidade</label>
                    <input type="text" id="city" name="city" value="<?php echo $user->city; ?>">
                </div>

                <div class="input-col">
                    <label for="address">Endereço</label>
                    <input type="text" id="address" name="address" value="<?php echo $user->address; ?>">
                </div>
            </div>

            <div class="button-row">
                <button type="submit" class="register-button">Confirmar Dados</button>
                <a href="<?= base_url('/item/' . $itemId) ?>" class="cancel-button btn">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>