<?php
/**
 * Vista de la página principal
 *
 * Esta vista muestra la página principal después de iniciar sesión
 *
 * @package app\Views\home
 */

if (!isset($user)) {
    echo "<div class='error-message'>Error: No se ha proporcionado la información del usuario</div>";
    return;
}
?>

<div class="dashboard-container">
    <div class="welcome-message">
        <h2><i class="welcome-icon"></i> Bienvenido, <?= htmlspecialchars($user['name']) ?></h2>
        <p>Has iniciado sesión correctamente en el sistema.</p>

        <div class="dashboard-info">
            <h3><i class="info-icon"></i> Información de tu cuenta</h3>
            <ul>
                <li>
                    <i class="email-icon"></i>
                    <span><strong>Correo electrónico:</strong> <?= htmlspecialchars($user['email']) ?></span>
                </li>
            </ul>
        </div>
    </div>
</div>
