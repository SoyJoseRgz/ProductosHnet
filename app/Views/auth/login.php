<?php
/**
 * Vista de login
 *
 * Esta vista muestra el formulario de inicio de sesión
 *
 * @package app\Views\auth
 */
?>

<div class="login-container">
    <div class="login-header">
        <h2>Bienvenido a Productos Hnet</h2>
        <p class="login-subtitle">Accede a tu cuenta</p>
    </div>

    <?php
    if (!empty($error)) {
        $viewService->includeComponent('error_message', ['message' => $error]);
    }
    ?>

    <form method="POST" action="<?= APP_URL ?>/login" class="login-form">
        <div class="form-group">
            <div class="input-icon-wrapper">
                <i class="input-icon email-icon"></i>
                <input type="email" id="email" name="email" class="form-control" placeholder="Correo Electrónico" required>
            </div>
        </div>

        <div class="form-group">
            <div class="input-icon-wrapper">
                <i class="input-icon password-icon"></i>
                <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn login-btn">Iniciar Sesión</button>
        </div>
    </form>
</div>
