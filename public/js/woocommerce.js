/**
 * JavaScript para la integración con WooCommerce
 */

document.addEventListener('DOMContentLoaded', function() {
    // Formulario de subida
    const uploadForm = document.querySelector('.upload-form');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            const categoryId = document.getElementById('category_id').value;
            const profitPercentage = document.getElementById('profit_percentage').value;
            const limit = document.getElementById('limit').value;
            
            // Validaciones básicas
            if (!categoryId) {
                e.preventDefault();
                alert('Por favor, seleccione una categoría.');
                return;
            }
            
            if (profitPercentage < 0 || profitPercentage > 100) {
                e.preventDefault();
                alert('El porcentaje de ganancia debe estar entre 0 y 100.');
                return;
            }
            
            if (limit < 1 || limit > 20) {
                e.preventDefault();
                alert('El límite debe estar entre 1 y 20 productos.');
                return;
            }
            
            // Mostrar mensaje de carga
            const submitButton = uploadForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="upload-icon"></i> Procesando...';
            
            // Continuar con el envío del formulario
        });
    }
    
    // Animación para tarjetas
    const cards = document.querySelectorAll('.action-card, .product-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
