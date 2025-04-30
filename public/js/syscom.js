/**
 * JavaScript para las vistas de SYSCOM
 * Versión simplificada que solo maneja la funcionalidad de categorías
 */
document.addEventListener('DOMContentLoaded', function() {
    // Mejorar la navegación en dispositivos móviles
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    if (dropdownToggle && window.innerWidth <= 768) {
        dropdownToggle.addEventListener('click', function(e) {
            // En móvil, al hacer clic en el toggle, mostrar/ocultar el menú
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu.style.display === 'block') {
                dropdownMenu.style.display = 'none';
            } else {
                dropdownMenu.style.display = 'block';
            }
            e.preventDefault();
        });
    }

    // Debug code removed
});
