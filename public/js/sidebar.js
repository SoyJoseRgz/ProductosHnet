/**
 * JavaScript para el sidebar
 */
document.addEventListener('DOMContentLoaded', function() {
    // Manejar clics en elementos con submenús
    const menuItemsWithSubmenu = document.querySelectorAll('.has-submenu');
    
    menuItemsWithSubmenu.forEach(item => {
        item.addEventListener('click', function(e) {
            // Solo prevenir el comportamiento predeterminado si se hace clic en el enlace principal
            if (e.target.classList.contains('has-submenu') || e.target.parentElement.classList.contains('has-submenu')) {
                e.preventDefault();
                
                // Obtener el elemento padre (li.sidebar-item)
                const parentItem = this.closest('.sidebar-item');
                
                // Alternar la clase active
                parentItem.classList.toggle('active');
                
                // Obtener el submenú
                const submenu = this.nextElementSibling;
                
                // Alternar la clase open
                submenu.classList.toggle('open');
            }
        });
    });
    
    // Agregar botón de toggle para móviles
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebar && window.innerWidth <= 576) {
        // Crear botón de toggle
        const toggleButton = document.createElement('div');
        toggleButton.className = 'sidebar-toggle';
        toggleButton.innerHTML = '☰';
        document.body.appendChild(toggleButton);
        
        // Manejar clic en el botón
        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
        
        // Cerrar sidebar al hacer clic en el contenido principal
        mainContent.addEventListener('click', function() {
            sidebar.classList.remove('open');
        });
    }
});
