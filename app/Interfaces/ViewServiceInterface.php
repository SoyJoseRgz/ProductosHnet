<?php
/**
 * Interfaz para el servicio de vistas
 * 
 * Define los métodos que debe implementar cualquier servicio de vistas
 * 
 * @package app\Interfaces
 */
namespace app\Interfaces;

interface ViewServiceInterface
{
    /**
     * Renderiza una vista con el layout principal
     * 
     * @param string $viewPath Ruta de la vista a renderizar
     * @param array $data Datos para pasar a la vista
     * @return void
     */
    public function render(string $viewPath, array $data = []): void;
}
