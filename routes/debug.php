<?php

use Illuminate\Support\Facades\Route;

Route::get('debug-routes', function () {
    $routes = Route::getRoutes();
    
    foreach ($routes as $route) {
        if (str_contains($route->getActionName(), 'Auth\\LoginController')) {
            echo "Found problematic route: " . $route->uri() . " -> " . $route->getActionName() . "\n";
        }
    }
});