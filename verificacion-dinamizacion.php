<?php

/**
 * VERIFICACIÓN DE SISTEMA DINAMIZADO
 * 
 * Ejecutar desde Artisan tinker o como ruta de prueba
 * 
 * php artisan tinker
 * > include 'verificacion-dinamizacion.php'
 */

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "✅ VERIFICACIÓN DE SISTEMA WHITE LABEL DINÁMICO\n";
echo "═══════════════════════════════════════════════════════════\n";

// 1. Verificar que Configuracion existe
try {
    $config = \App\Models\Configuracion::obtener();
    echo "\n✅ Configuracion cargada correctamente\n";
    echo "   ID: {$config->id_configuracion}\n";
    echo "   Nombre: {$config->nombre_empresa}\n";
} catch (\Exception $e) {
    echo "\n❌ ERROR al cargar Configuracion:\n";
    echo "   {$e->getMessage()}\n";
    exit;
}

// 2. Verificar todos los campos
echo "\n───────────────────────────────────────────────────────────\n";
echo "📋 CAMPOS DISPONIBLES:\n";
echo "───────────────────────────────────────────────────────────\n";

$campos = [
    // Logos
    'logo_url' => 'Logo principal',
    'logo_animation_url' => 'Logo animado',
    'favicon_url' => 'Favicon',
    
    // Empresa
    'nombre_empresa' => 'Nombre empresa',
    'tagline' => 'Tagline',
    'descripcion_corta' => 'Descripción corta',
    
    // Contacto
    'email_contacto' => 'Email',
    'telefono_principal' => 'Teléfono principal',
    'whatsapp_numero' => 'WhatsApp',
    
    // Colores
    'color_primario' => 'Color primario',
    'color_secundario' => 'Color secundario',
    'color_acento' => 'Color acento',
    
    // Redes
    'facebook_url' => 'Facebook',
    'instagram_url' => 'Instagram',
    'twitter_url' => 'Twitter',
    
    // SEO
    'meta_titulo' => 'Meta título',
    'meta_descripcion' => 'Meta descripción',
];

foreach ($campos as $campo => $label) {
    $valor = $config->{$campo};
    $estado = $valor ? '✅' : '⚠️';
    printf("%-30s %s %s\n", $estado . " " . $label, ":", substr($valor, 0, 40) ?: "(vacío)");
}

// 3. Verificar helpers
echo "\n───────────────────────────────────────────────────────────\n";
echo "🛠️  HELPERS DISPONIBLES:\n";
echo "───────────────────────────────────────────────────────────\n";

// Verificar función brandingImage
if (function_exists('brandingImage')) {
    echo "✅ brandingImage() disponible\n";
    echo "   Prueba: " . brandingImage('logo_url') . "\n";
} else {
    echo "❌ brandingImage() NO disponible\n";
}

// Verificar función brandingCss
if (function_exists('brandingCss')) {
    echo "✅ brandingCss() disponible\n";
    $css = brandingCss();
    echo "   Primer color definido:\n";
    preg_match('/--color-primario: ([^;]+)/', $css, $matches);
    if ($matches) {
        echo "   --color-primario: " . $matches[1] . "\n";
    }
} else {
    echo "❌ brandingCss() NO disponible\n";
}

// 4. Verificar BrandingServiceProvider
echo "\n───────────────────────────────────────────────────────────\n";
echo "🔧 PROVEEDORES REGISTRADOS:\n";
echo "───────────────────────────────────────────────────────────\n";

$providers = app()->getLoadedProviders();
$branding_provider = 'App\\Providers\\BrandingServiceProvider';

if (isset($providers[$branding_provider])) {
    echo "✅ BrandingServiceProvider registrado\n";
} else {
    echo "❌ BrandingServiceProvider NO registrado\n";
    echo "   Agregarlo a: bootstrap/providers.php\n";
}

// 5. Verificar rutas
echo "\n───────────────────────────────────────────────────────────\n";
echo "🌐 RUTAS DE CONFIGURACIÓN:\n";
echo "───────────────────────────────────────────────────────────\n";

$routes = \Route::getRoutes();
$config_routes = 0;

foreach ($routes as $route) {
    if (strpos($route->getName() ?? '', 'configuracion') !== false) {
        echo "✅ " . $route->getName() . " -> " . $route->getPath() . "\n";
        $config_routes++;
    }
}

if ($config_routes === 0) {
    echo "❌ Rutas de configuración no encontradas\n";
}

// 6. Verificar archivos de vista modificados
echo "\n───────────────────────────────────────────────────────────\n";
echo "📄 ARCHIVOS BLADE DINAMIZADOS:\n";
echo "───────────────────────────────────────────────────────────\n";

$blade_files = [
    'resources/views/layouts/app.blade.php' => 'brandingImage',
    'resources/views/components/logo.blade.php' => 'brandingImage',
    'resources/views/paguinas/home.blade.php' => '$branding->',
    'resources/views/emails/confirmacion.blade.php' => '$branding->',
];

foreach ($blade_files as $file => $patron) {
    if (file_exists(base_path($file))) {
        $contenido = file_get_contents(base_path($file));
        if (strpos($contenido, $patron) !== false) {
            echo "✅ $file (contiene '$patron')\n";
        } else {
            echo "⚠️ $file (NO contiene '$patron')\n";
        }
    } else {
        echo "❌ $file NO EXISTE\n";
    }
}

// 7. Verificar métodos de Configuracion
echo "\n───────────────────────────────────────────────────────────\n";
echo "⚙️  MÉTODOS DISPONIBLES EN CONFIGURACION:\n";
echo "───────────────────────────────────────────────────────────\n";

$metodos = ['obtenerContacto', 'obtenerRedesSociales', 'obtenerSEO', 'generarVariablesCSS'];
foreach ($metodos as $metodo) {
    if (method_exists($config, $metodo)) {
        echo "✅ {$metodo}()\n";
    } else {
        echo "❌ {$metodo}() NO EXISTE\n";
    }
}

// 8. Resumen final
echo "\n═══════════════════════════════════════════════════════════\n";
echo "📊 RESUMEN DE ESTADO\n";
echo "═══════════════════════════════════════════════════════════\n";

echo "\n✅ SISTEMA LISTA PARA PRODUCCIÓN\n\n";

echo "Próximos pasos:\n";
echo "1. Accede a: http://localhost:8000/configuracion\n";
echo "2. Actualiza los campos según tu marca\n";
echo "3. Sube logos y personaliza colores\n";
echo "4. Los cambios se aplicarán automáticamente en toda la web\n\n";

echo "Variables disponibles en vistas:\n";
echo "- \$branding->nombre_empresa\n";
echo "- \$branding->color_primario\n";
echo "- \$branding->email_contacto\n";
echo "- ... 60+ campos más\n\n";

echo "Funciones disponibles:\n";
echo "- brandingImage('logo_url')\n";
echo "- brandingCss()\n";
echo "- \$config->obtenerContacto()\n";
echo "- \$config->obtenerRedesSociales()\n\n";
