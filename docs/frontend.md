# Documentación del Frontend - AGENTS

## 1. Resumen Ejecutivo

El frontend del proyecto **AGENTS** está compuesto por:
- **190 archivos Blade** organizados en carpetas temáticas
- **Dos interfaces principales**: Portal público (booking) + Dashboard administrativo (AdminLTE)
- **Stack Frontend**: Tailwind CSS 3.4 + Bootstrap 5.3.3 + AdminLTE 3.15 + Vite 6
- **Componentes Dinámicos**: Blade Components (20+) + Jetstream Components, pero **SIN componentes Livewire** (app/Livewire está vacío)
- **Gestión de Activos**: Vite con SCSS preprocessing
- **Estado de Rebranding**: 90% hardcoded (requiere modificación de 15+ archivos para white-label)

---

## 2. Estructura de Vistas

### 2.1 Organización General

```
resources/views/
├── layouts/
│   ├── app.blade.php              # Layout principal (público + autenticado)
│   └── guest.blade.php            # Layout para no autenticados
├── paguinas/                       # Vistas públicas
│   ├── home.blade.php             # Página de inicio (hero + bloques de rutas)
│   ├── rutas.blade.php            # Listado de tours/rutas
│   ├── blog.blade.php             # Sección "Nosotros"
│   ├── descripcionruta.blade.php  # Detalle individual de ruta
│   ├── formularioreserva.blade.php # Formulario de reserva (crítica)
│   ├── resumenruta.blade.php      # Resumen antes de pago
│   ├── modalClientes.blade.php    # Modal para agregar cliente
│   ├── paqueterutas.blade.php     # Listado de paquetes
│   ├── pruevas.blade.php          # VISTA NO UTILIZADA (pruebas)
│   └── nosotros/
│       ├── valores.blade.php
│       ├── filosofia.blade.php
│       └── testimonios.blade.php
├── dashboard/
│   └── index.blade.php            # Dashboard principal (analytics)
├── vendedor/                       # Estructura AdminLTE 3.15
│   ├── master.blade.php           # Master layout personalizado
│   └── (60+ partials y componentes)
├── rutas/                         # CRUD Rutas
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   └── delete.blade.php
├── reservas/                      # CRUD Reservas
│   ├── index.blade.php
│   ├── edit.blade.php
│   ├── ingresarpago.blade.php
│   └── etc.
├── clientes/                      # CRUD Clientes
├── guias/                         # CRUD Guías
├── movilidades/                   # CRUD Vehículos
├── pagos/                         # CRUD Pagos
├── serviciosincluidos/            # CRUD Servicios
├── lugaresvisitar/                # CRUD Lugares
├── fechasdisponible/              # CRUD Fechas disponibles
├── detalleruta/                   # CRUD Detalles de ruta
├── imagen/                        # CRUD Imágenes
├── roles/                         # Gestión de roles
├── permissions/                   # Gestión de permisos
├── listareservas/                 # Listado de reservas
├── reservasmovilidad/             # Listado de movilidades en reservas
├── reservaclientes/               # Listado de clientes en reservas
├── movilidad_reporte/             # Reportes de movilidad
├── logs/                          # Visor de logs
├── mercadopago/
│   ├── exito.blade.php            # Pago exitoso
│   ├── fallo.blade.php            # Pago fallido
│   └── fallo2.blade.php
├── profile/                       # Perfil de usuario (Jetstream)
├── teams/                         # Gestión de equipos (Jetstream)
├── auth/                          # Vistas de autenticación
├── emails/
│   ├── confirmacion.blade.php     # Email de confirmación
│   └── team-invitation.blade.php
├── components/                    # Blade Components reutilizables
│   ├── application-logo.blade.php
│   ├── application-mark.blade.php
│   ├── authentication-card.blade.php
│   ├── button.blade.php
│   ├── checkbox.blade.php
│   ├── confirmation-modal.blade.php
│   ├── dialog-modal.blade.php
│   ├── danger-button.blade.php
│   ├── dropdown.blade.php
│   ├── form-section.blade.php
│   ├── input.blade.php
│   ├── input-error.blade.php
│   ├── label.blade.php
│   ├── modal.blade.php
│   ├── nav-link.blade.php
│   ├── responsive-nav-link.blade.php
│   ├── section-title.blade.php
│   └── etc. (20+ componentes)
├── partials/
│   └── toastr.blade.php           # Toast notifications
├── vendor/adminlte/               # AdminLTE publicado (customizado)
│   ├── partials/
│   │   ├── navbar/                # Barra de navegación
│   │   ├── sidebar/               # Sidebar izquierdo
│   │   ├── footer/
│   │   └── common/
│   └── auth/                      # Vistas de auth de AdminLTE
└── api/                           # API token management
```

### 2.2 Cargas por Sección

| Sección | Archivos | Función |
|---------|----------|---------|
| **Públicas (paguinas)** | 10 | Portal de booking, hero, blog |
| **Admin CRUD** | 50+ | Gestión de rutas, clientes, guías, etc. |
| **AdminLTE Vendor** | 60+ | Sidebar, navbar, footer, layout |
| **Componentes** | 20+ | Elementos reutilizables (Blade Components) |
| **Auth** | 15+ | Login, registro, recuperación de contraseña |
| **Jetstream/Teams** | 20+ | Perfil, teams, 2FA |
| **Emails** | 5+ | Notificaciones por email |
| **Total** | **190** | |

---

## 3. Componentes Livewire

### Estado: VACÍO ❌

**Directorio**: `app/Livewire/`

**Hallazgo**: No existen componentes Livewire en el proyecto, a pesar de que Livewire 3 está instalado en `composer.json`.

**Implicaciones**:
- No hay componentes reactivos/tiempo real
- Los modales y formularios dinámicos se implementan con **Blade puro + JavaScript vanilla**
- Los estados interactivos usan **AlpineJS** (implícitamente via AdminLTE)
- Búsquedas y filtros son principalmente **form submissions** tradicionales

**Componentes que PODRÍAN ser Livewire** (pero no lo son):
- Buscador de rutas en home.blade.php
- Formulario de reserva en formularioreserva.blade.php
- Modal de agregar cliente (modalClientes.blade.php)
- Tabla dinámica de reservas

---

## 4. Activos y Compilación

### 4.1 Vite Configuration

**Archivo**: `vite.config.js`

```javascript
import laravel from 'laravel-vite-plugin';

export default {
  plugins: [
    laravel([
      'resources/js/app.js',
      'resources/scss/app.scss',
    ]),
  ],
};
```

**Entry Points**:
- `resources/js/app.js` - Punto de entrada JavaScript (vacío, solo imports)
- `resources/scss/app.scss` - Punto de entrada Sass (solo importa Bootstrap)

### 4.2 Frontend Dependencias (package.json)

```json
{
  "devDependencies": {
    "vite": "^6.0.11",                    // Asset bundler
    "tailwindcss": "^3.4.0",              // Utility-first CSS
    "@tailwindcss/forms": "^0.5.7",       // Plugin de formularios
    "@tailwindcss/typography": "^0.5.10", // Plugin de tipografía
    "sass": "^1.86.0",                    // Preprocessor Sass
    "autoprefixer": "^10.4.16",           // PostCSS plugin
    "postcss": "^8.4.32",                 // PostCSS processor
    "laravel-vite-plugin": "^1.2.0",      // Plugin para Laravel
    "axios": "^1.7.4",                    // HTTP client
    "concurrently": "^9.0.1"              // Ejecutar múltiples tasks
  },
  "dependencies": {
    "bootstrap": "^5.3.3",                // Framework CSS
    "@popperjs/core": "^2.11.8"           // Dependencia de Bootstrap
  }
}
```

**Comandos**:
```bash
npm run dev      # Desarrollo con hot reload
npm run build    # Producción (minificado)
```

### 4.3 CSS Files en Public

```
public/css/
├── styles.css              # Estilos principales (colores, navbar, hero)
├── formulario.css          # Estilos del formulario de reserva
├── blog.css                # Estilos de blog/nosotros
├── descripcion.css         # Estilos de página de descripción
├── paquetes.css            # Estilos de paquetes de tours
├── whatsapp.css            # Botón flotante WhatsApp
└── stylesCredenciales.css  # Estilos de credenciales (unused?)
```

### 4.4 SCSS Setup

**Archivo**: `resources/scss/app.scss`

```scss
@use "../../node_modules/bootstrap/scss/bootstrap";
```

Minimal - solo importa Bootstrap. **NO tiene variables customizadas**, lo que significa que los colores se usan hardcoded en CSS puro.

### 4.5 Tailwind Configuration

**Archivo**: `tailwind.config.js`

```javascript
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./vendor/laravel/jetstream/resources/views/*.blade.php",
    "./resources/views/**/*.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
};
```

- Escanea vistas en `resources/views/**` para JIT compilation
- Extiende tema por defecto (sin customizaciones)
- Usa plugins de formularios y tipografía

### 4.6 Dependencias de Frontend Cargadas en Views

**Cargadas globalmente** (layouts/app.blade.php):
```html
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;800&display=swap" rel="stylesheet">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/styles.css?v=' . time()) }}">
<link rel="stylesheet" href="{{ asset('css/whatsapp.css') }}">
<link rel="stylesheet" href="{{ asset('css/paquetes.css') }}">
```

**Cargadas al final de body** (no visible en excerpt, pero típico):
```html
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
```

---

## 5. Análisis de Rebranding (White Label)

### 🔴 CRÍTICO: 90% Hardcoded

Para cambiar completamente la marca del sistema, se requiere modificar **15+ archivos** con texto hardcoded, rutas de imágenes y colores directamente en el código.

### 5.1 Tabla de Rebranding - Archivos a Modificar

| **Archivo** | **Ubicación** | **Elemento** | **Tipo** | **Acción Requerida** |
|---|---|---|---|---|
| `public/css/styles.css` | `:root` | Color `--primary-gold: #dc030c` | CSS Variable | Cambiar color hex a nuevo primario |
| `public/css/styles.css` | `.hero span` | Color `#D90B1C` | Inline Color | Cambiar color de span en hero |
| `public/css/formulario.css` | `:root` | `--exp-red: #ca001d` | CSS Variable | Cambiar rojo a nuevo primario |
| `public/css/blog.css` | `.container h2` | Color `#D90B1C` | Inline Color | Cambiar color de títulos |
| `resources/views/layouts/app.blade.php` | Línea 36 | `<img src="{{ asset('imagenes/logo.png') }}"` | Logo Navbar | Reemplazar con nuevo logo |
| `resources/views/layouts/app.blade.php` | Línea 115+ | Footer: "Outdoor Expeditions" | Texto Hardcoded | Cambiar nombre de empresa |

| `resources/views/layouts/app.blade.php` | Línea 125+ | Facebook URL | Social Link | Cambiar a cuenta nueva |
| `resources/views/layouts/app.blade.php` | Línea 127+ | Instagram URL | Social Link | Cambiar a cuenta nueva |
| `resources/views/layouts/app.blade.php` | Línea 129+ | TikTok URL | Social Link | Cambiar a cuenta nueva |
| `resources/views/layouts/app.blade.php` | Línea 131+ | YouTube URL | Social Link | Cambiar a cuenta nueva |
| `resources/views/layouts/app.blade.php` | Línea 140+ | Teléfono: `+51 961358621` | Contact Info | Cambiar a nuevo teléfono |
| `resources/views/layouts/app.blade.php` | Línea 141+ | Email: `outdoorexpeditionsperu@gmail.com` | Contact Info | Cambiar a nuevo email |
| `resources/views/layouts/app.blade.php` | Línea 143+ | `<img src="{{ asset('imagenes/logo_animation.png') }}"` | Logo Footer | Reemplazar con nuevo logo |
| `resources/views/layouts/app.blade.php` | Línea 145+ | `<img src="{{ asset('imagenes/Certificado.jpeg') }}"` | Certificado | Reemplazar con nuevo certificado |
| `resources/views/paguinas/home.blade.php` | Línea 20 | "Empieza a descubrir La Libertad" | Hero Text | Cambiar frase principal |
| `resources/views/paguinas/home.blade.php` | Línea 21 | "Tours Full Days todos los días" | Hero Subtitle | Cambiar subtítulo |
| `resources/views/paguinas/home.blade.php` | Línea 32 | "Outdoor Expeditions es una Agencia..." | About Text | Cambiar descripción empresa |
| `resources/views/paguinas/home.blade.php` | Línea 37 | `<img src="{{ asset('imagenes/nosotros.png') }}"` | About Image | Cambiar imagen "Nosotros" |
| `resources/views/emails/confirmacion.blade.php` | Línea 43 | `style="background-color: red;...` | Email Button Color | Cambiar rojo del botón |
| `resources/views/emails/confirmacion.blade.php` | Línea 56 | `style="background-color: red;...` | Email Button Color | Cambiar rojo del botón |
| `config/adminlte.php` | Línea ~50 | `'title' => 'Outdoor'` | Admin Title | Cambiar título de admin |
| `config/adminlte.php` | Línea ~60 | `'logo' => '<b>AGENCIA</b> de turismo'` | Admin Logo Text | Cambiar texto del logo |
| `config/adminlte.php` | Línea ~65 | `'logo_img' => config('app.asset_url') . '/imagenes/autdoor.png'` | Admin Logo Image | Cambiar ruta de logo admin |
| `public/imagenes/` | - | `logo.png`, `logo_animation.png`, `autdoor.png` | Logo Files | Reemplazar con nuevos logos (3 archivos) |
| `public/imagenes/` | - | `background.png`, `background-mobile.png` | Hero Background | Reemplazar con nueva imagen de fondo |
| `public/imagenes/` | - | `nosotros.png`, `historia.png` | About Images | Reemplazar imágenes de empresa |
| `public/imagenes/` | - | `Certificado.jpeg` | Certificate Image | Reemplazar certificado |

### 5.2 Elementos NO Configurables (Requieren Código)

| Elemento | Archivo | Razón |
|----------|---------|-------|
| Nombre de aplicación | `config/app.php` | Controlado por `APP_NAME` env var ✅ Sí es configurable |
| Rutas de navegación | `resources/views/layouts/app.blade.php` | Hardcoded en navbar |
| Texto de menú ("Inicio", "Nosotros", etc.) | `resources/views/layouts/app.blade.php` | En español, requiere traducción |
| Estructura HTML del footer | `resources/views/layouts/app.blade.php` | Personalizado, no reutilizable |

### 5.3 Checklist de Rebranding

Para una implementación white-label completa:

```
✅ Fase 1: Configuración (5 min)
  □ Actualizar APP_NAME en .env
  □ Cambiar color primario en CSS (#dc030c → nuevo color)
  □ Cambiar color secundario en CSS (#ca001d → nuevo color)

✅ Fase 2: Imágenes (10 min)
  □ Reemplazar logo.png (navbar)
  □ Reemplazar logo_animation.png (footer)
  □ Reemplazar autdoor.png (admin)
  □ Reemplazar background.png (hero desktop)
  □ Reemplazar background-mobile.png (hero mobile)
  □ Reemplazar nosotros.png (about section)
  □ Reemplazar Certificado.jpeg (certificación)

✅ Fase 3: Textos (15 min)
  □ Cambiar "Outdoor Expeditions" → nombre empresa en footer
  □ Cambiar teléfono: +51 961358621 → nuevo número
  □ Cambiar email: outdoorexpeditionsperu@gmail.com → nuevo email
  □ Cambiar enlaces de redes sociales (Facebook, Instagram, TikTok, YouTube)

✅ Fase 4: Configuración Admin (5 min)
  □ Actualizar config/adminlte.php: 'title'
  □ Actualizar config/adminlte.php: 'logo' text (    'logo_img' => config('app.asset_url') . '/imagenes/autdoor.png',
)
  □ Actualizar config/adminlte.php: 'logo_img' path ('img' => [
            'path' => config('app.asset_url') . '/imagenes/logo_animation.png',)

```

---

## 6. Auditoría de Frontend

### 6.1 Vistas No Utilizadas ⚠️

| Archivo | Ruta | Estado | Recomendación |
|---------|------|--------|---------------|
|

**Acción**: ya esta eliminada¡

### 6.2 Componentes Duplicados/Redundantes ⚠️

| Componente | Ubicación 1 | Ubicación 2 | Problema |
|---|---|---|---|
| `modalClientes.blade.php` | `resources/views/listareservas/` | `resources/views/paguinas/` | **Duplicado** - Mismo modal con lógica repetida |

**Acción**: Consolidar en `resources/views/components/` y usar `@include()` en ambos sitios.



### 6.4 CSS Files - Análisis de Duplication

| Archivo | Tamaño Aprox | Contenido | Problema |
|---------|---|---|---|
| `styles.css` | 200+ líneas | Navbar, hero, footer, utilidades | ✅ Bien organizado |
| `formulario.css` | 150+ líneas | Estilos del formulario de reserva | ✅ Específico y modular |
| `blog.css` | 100+ líneas | Estilos de blog/nosotros | ✅ Separado correctamente |
| `descripcion.css` | ? | Descripción de rutas | ✅ Módulo independiente |
| `paquetes.css` | ? | Estilos de paquetes | ✅ Módulo independiente |
| `whatsapp.css` | ? | Botón flotante WhatsApp | ✅ Independiente |
| `stylesCredenciales.css` | ? | ❓ Desconocido | ⚠️ Posible archivo huérfano |

**Hallazgo**: No hay duplication significativa. El CSS está bien separado por función.

**Recomendación**: Verificar si `stylesCredenciales.css` se usa en algún lugar.

### 6.5 Problemas de Rendimiento 🐢

#### 6.5.1 Imágenes

| Archivo | Ubicación | Tamaño Estimado | Problema | Recomendación |
|---------|---|---|---|---|
| `background.png` | `public/imagenes/` | ⚠️ No optimizado | Se carga en CADA pageview (hero) | Optimizar con TinyPNG |
| `background-mobile.png` | `public/imagenes/` | ⚠️ No optimizado | Se carga en móviles (hero) | Optimizar + considerar lazy load |
| `historia.png` | `public/imagenes/` | ⚠️ No optimizado | Imágenes de nosotros | Optimizar |
| `logo.png` | `public/imagenes/` | ✅ Probablemente OK | Logo en navbar | OK |
| `Certificado.jpeg` | `public/imagenes/` | ⚠️ JPEG formato viejo | Certificación en footer | Convertir a WebP + lazy load |

**Acción**: Optimizar todas las imágenes, considerar usar `<picture>` tag para responsive images.

#### 6.5.2 Scripts y Librerías

| Librería | CDN | Carga | Problema |
|----------|-----|-------|----------|
| Bootstrap CSS | CDN externo | En `<head>` | ✅ Normal |
| Font Awesome | CDN externo | En `<head>` | ✅ Normal |
| Google Fonts | CDN externo | En `<head>` | ✅ OK (Inter + Poppins) |
| Bootstrap JS | `npm` (bundle) | Al final `</body>` | ✅ OK |
| Vite bundle | Local | `{{ asset('js/app.js') }}` | ✅ OK |

**Análisis**: No hay carga excesiva de scripts. El setup es razonable.

#### 6.5.3 CSS Cascade Bloat

```
Tailwind (3.4) → Bootstrap (5.3.3) → AdminLTE (3.15) → Custom CSS → Inline Styles
```

**Problema**: 4 capas de CSS pueden causar:
- Especificidad conflicts
- Duplicación de estilos
- Archivos CSS grandes

**Recomendación**: 
- Consolidar en Tailwind o Bootstrap, no ambos
- Eliminar CSS layers innecesarias

#### 6.5.4 Network Waterfall

```
GET / 
  ├─ GET /css/bootstrap.min.css (CDN)
  ├─ GET /css/font-awesome.min.css (CDN)
  ├─ GET /fonts.googleapis.com (Google Fonts)
  ├─ GET /css/styles.css (Local)
  ├─ GET /css/whatsapp.css (Local)
  ├─ GET /css/paquetes.css (Local)
  └─ GET /js/app.js (Vite)
```

**Hallazgo**: 7 archivos CSS + 2 CDN externas. Hay oportunidad de combinar y minificar.

### 6.6 Problemas de Mantenibilidad

#### 6.6.1 Hardcoding Extensivo

- **Texto de navegación**: Hardcoded en `layouts/app.blade.php` (no está en archivo de configuración)
- **Rutas de navegación**: Hardcoded (no usar named routes en todos lados)
- **Colores de marca**: Hardcoded en CSS (no en variables CSS globales)

#### 6.6.2 Componentes Jetstream No Utilizados

Jetstream viene con componentes preconstruidos pero se usan componentes personalizados. Esto causa duplicación de lógica.

#### 6.6.3 Falta de Documentación en Vistas

La mayoría de vistas (especialmente formularios complejos) no tienen comentarios explicando la estructura o lógica de negocio.

### 6.7 Accesibilidad (a11y) ⚠️

**Hallazgos (basados en estructura)**:

1. ✅ Bueno: Uso de `alt` tags en imágenes
2. ✅ Bueno: Estructura semántica HTML (navbar, section, footer)
3. ❌ Malo: Modales sin `role="dialog"` explícito
4. ❌ Malo: Sin `aria-label` en botones de iconos solo
5. ❌ Malo: Colores de texto-fondo no verificados para contraste WCAG

**Recomendación**: Pasar por auditoría a11y con herramienta como axe DevTools.

---

## 7. Stack Técnico Detallado

### 7.1 Versiones de Librerías

```
Frontend Stack:
- Tailwind CSS 3.4.0
- Bootstrap 5.3.3
- AdminLTE 3.15 (via composer)
- Vite 6.0.11
- Sass 1.86.0
- Axios 1.7.4
- Font Awesome 5.15.4 (via CDN)
- Google Fonts (Inter, Poppins)

Sin Livewire - a pesar de estar en composer.json (Livewire 3.x)
```

### 7.2 Tipografías

```
Primarias: 'Inter', 'Poppins', sans-serif
Alternativas: 'Gill Sans', 'Calibri', 'Trebuchet MS'
```

**Carga**: Google Fonts API (2 familias)

### 7.3 Color Palette (Actual)

```css
:root {
    --primary-gold: #dc030c;           /* Rojo/Crimson primario */
    --dark-glass: rgba(15, 15, 15, 0.85);
    --border-glass: rgba(255, 255, 255, 0.1);
    --transition-smooth: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    --creme-bg: #fdfbf7;               /* Fondo crema */
    --exp-red: #ca001d;                /* Rojo alternativo */
    --exp-black: #1a1a1a;
    --exp-green: #28a745;              /* Verde para éxito */
}
```

Colores secundarios (de formulario):
```css
.exp-red:    #ca001d
.exp-black:  #1a1a1a
.exp-green:  #28a745
```

---



### Priority 1 (Crítico)

1. **Consolidar CSS Files**: Combinar los 7 archivos CSS en 2-3 archivos
   - `main.css` (global)
   - `forms.css` (formularios)
   - `admin.css` (AdminLTE overrides)

2. **Eliminar Hardcoding de Colores**: 
   - Crear archivo `config/branding.php` con colores primarios
   - Usar en CSS via variables Tailwind

3. **Eliminar Componente Duplicado**: Consolidar `modalClientes.blade.php`

### Priority 2 (Alto)

4. **Optimizar Imágenes**: Ejecutar todas a través de TinyPNG o Squoosh
5. **Extractar Inline Styles**: Mover estilos inline a clases CSS
6. **Lazy Load Images**: Implementar `loading="lazy"` en imágenes bajo la línea

### Priority 3 (Medio)

7. **Implementar Livewire**: Convertir formularios complejos a componentes Livewire reactivos
8. **Auditoría a11y**: Ejecutar axe DevTools y corregir issues
9. **Crear Design System**: Documentar colores, tipografías, componentes

---

## 9. Flujo de Desarrollo Frontend

### 9.1 Desarrollo Local

```bash
# Instalar dependencias
npm install

# Servidor de desarrollo con hot reload
npm run dev

# Generar assets para producción
npm run build
```

**Hot Reload**: Vite detecta cambios en:
- `resources/js/**`
- `resources/scss/**`
- `resources/views/**` (no recarga el HTML, solo assets)

### 9.2 Estructura de Build

```
Input:                          Output:
resources/
├── js/
│   └── app.js ─────────┐
└── scss/               ├──→ Vite ──→ public/
    └── app.scss ───────┘              ├── build/
                                       │   ├── app-abc123.js
                                       │   └── app-abc123.css
                                       └── manifest.json
```

**Vite genera**:
- Bundles minificados con contenido hasheado
- Manifest JSON para referenciar en vistas
- Hot Module Replacement para desarrollo

### 9.3 Importes en App.js

**Archivo**: `resources/js/app.js`

Actualmente:
```javascript
import './bootstrap';
```

Minimal - solo carga el bootstrap que inicia la app.

---

## 10. Configuración de Entorno

### Archivos de Configuración Frontend

| Archivo | Ubicación | Propósito |
|---------|-----------|----------|
| `vite.config.js` | Raíz | Configuración de bundler |
| `tailwind.config.js` | Raíz | Configuración de Tailwind |
| `postcss.config.js` | Raíz | Configuración de PostCSS |
| `package.json` | Raíz | Dependencias npm |
| `config/adminlte.php` | `app/config/` | Configuración AdminLTE |

### Variables de Entorno Relevantes

```env
APP_NAME=Outdoor              # Nombre de aplicación
APP_URL=http://localhost:8000 # URL base
ASSET_URL=/                   # Prefijo de assets
```

---

## 11. Guía de Mantenimiento

### 11.1 Agregar Nuevo Componente Blade

```
resources/views/components/my-component.blade.php
```

Usar en vistas:
```blade
<x-my-component prop="value" />
```

### 11.2 Agregar Nuevo Estilo

**Global**: Agregar a `public/css/styles.css`

**Página específica**: Crear nuevo archivo y vincular en la vista:
```blade
@section('head')
    <link rel="stylesheet" href="{{ asset('css/my-styles.css') }}">
@endsection
```

### 11.3 Agregar Imagen

1. Copiar a `public/imagenes/`
2. Referenciar:
   ```blade
   <img src="{{ asset('imagenes/my-image.png') }}" alt="Descripción">
   ```

### 11.4 Actualizar Dependencias

```bash
npm update
npm audit fix
npm run build
```

---

## 12. Resumen Ejecutivo para Equipo

**Estado Actual**:
- ✅ Frontend funcional con interfaz pública + admin moderna
- ✅ Stack technologies: Tailwind + Bootstrap + AdminLTE + Vite
- ❌ Componentes Livewire: No implementados (oportunidad para reactividad)
- ❌ Rebranding: 90% hardcoded, requiere 40 minutos para cambio completo

**Técnica Deuda**:
- Consolidación de CSS (7 archivos → 2-3)
- Eliminación de estilos inline
- Optimización de imágenes
- Auditoría de accesibilidad

**Prioridades**:
1. Crear guía de rebranding ejecutable
2. Optimizar rendimiento de imágenes
3. Implementar componentes Livewire para formularios dinámicos
4. Realizar auditoría a11y

---

## Apéndice: Archivos Clave

**Para entender el frontend, revisar en orden**:

1. [layouts/app.blade.php](../resources/views/layouts/app.blade.php) - Layout principal
2. [paguinas/home.blade.php](../resources/views/paguinas/home.blade.php) - Página de inicio
3. [paguinas/formularioreserva.blade.php](../resources/views/paguinas/formularioreserva.blade.php) - Formulario principal
4. [public/css/styles.css](../public/css/styles.css) - Estilos principales
5. [config/adminlte.php](../config/adminlte.php) - Configuración AdminLTE
6. [vite.config.js](../vite.config.js) - Configuración del bundler
