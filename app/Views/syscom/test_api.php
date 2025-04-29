<div class="syscom-container">
    <h1>Prueba de Conexión a API SYSCOM</h1>
    
    <div class="test-results">
        <?php
        // Obtener el servicio de API
        $apiService = \app\Services\ServiceFactory::getSyscomApiService();
        
        // Probar obtención de token
        echo "<h2>Prueba de Token</h2>";
        $token = $apiService->getAccessToken();
        if (!empty($token)) {
            echo "<div class='success'>✅ Token obtenido correctamente: " . substr($token, 0, 10) . "...</div>";
        } else {
            echo "<div class='error'>❌ Error al obtener token. Verifique sus credenciales.</div>";
        }
        
        // Probar endpoint de categorías
        echo "<h2>Prueba de Endpoint de Categorías</h2>";
        $categories = $apiService->request('GET', 'categorias');
        
        echo "<pre>";
        if ($categories !== null) {
            echo "<div class='success'>✅ Respuesta recibida del endpoint de categorías</div>";
            echo "<h3>Respuesta completa:</h3>";
            print_r($categories);
        } else {
            echo "<div class='error'>❌ Error al obtener categorías. La respuesta es nula.</div>";
            
            // Intentar con otro endpoint para verificar si la API funciona
            echo "<h3>Intentando con otro endpoint (productos):</h3>";
            $products = $apiService->request('GET', 'productos', ['pagina' => 1, 'limite' => 1]);
            if ($products !== null) {
                echo "<div class='success'>✅ El endpoint de productos funciona correctamente</div>";
                echo "<h4>Muestra de respuesta:</h4>";
                print_r($products);
            } else {
                echo "<div class='error'>❌ Error al obtener productos. Posible problema general con la API.</div>";
            }
        }
        echo "</pre>";
        ?>
    </div>
    
    <div class="navigation-links">
        <a href="<?= APP_URL ?>/syscom" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Productos
        </a>
    </div>
</div>

<style>
.test-results {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-family: monospace;
}
.success {
    color: #28a745;
    font-weight: bold;
    margin-bottom: 10px;
}
.error {
    color: #dc3545;
    font-weight: bold;
    margin-bottom: 10px;
}
pre {
    background-color: #f1f1f1;
    padding: 15px;
    border-radius: 4px;
    overflow: auto;
    max-height: 500px;
}
</style>
