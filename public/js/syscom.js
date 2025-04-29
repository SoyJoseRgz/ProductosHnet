/**
 * JavaScript para las vistas de SYSCOM
 */
document.addEventListener('DOMContentLoaded', function() {
    // Manejo de imágenes en la página de detalles del producto
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.querySelector('.main-image img');

    if (thumbnails.length > 0 && mainImage) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const imgSrc = this.querySelector('img').src;
                const bigImgSrc = imgSrc.replace('imagen_chica', 'imagen_grande');
                mainImage.src = bigImgSrc;
            });
        });
    }

    // Validación del formulario de búsqueda
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                alert('Por favor, ingrese un término de búsqueda.');
                searchInput.focus();
            }
        });

        // Actualizar automáticamente al cambiar filtros
        const filterSelects = searchForm.querySelectorAll('select[name="limit"], select[name="sort"]');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                // Solo enviar si hay un término de búsqueda o una categoría seleccionada
                const searchInput = searchForm.querySelector('input[name="q"]');
                const categorySelect = searchForm.querySelector('select[name="category"]');

                if (searchInput.value.trim() || (categorySelect && categorySelect.value)) {
                    searchForm.submit();
                }
            });
        });

        // Manejar selección de categoría
        const categorySelect = searchForm.querySelector('select[name="category"]');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                // Si se selecciona una categoría, permitir búsqueda sin término
                const searchInput = searchForm.querySelector('input[name="q"]');

                if (this.value) {
                    // Si hay una categoría seleccionada, enviar el formulario
                    searchForm.submit();
                }
            });
        }
    }

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

    // Mostrar mensaje cuando se carga la página
    console.log('SYSCOM JS cargado correctamente');
});
