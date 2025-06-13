<!-- resources/views/emails/confirmacion.blade.php -->
@component('mail::message')
# ¡Hola {{ $cliente->nombre }} {{ $cliente->apellido }}!

Tu reserva ha sido confirmada exitosamente.

**Ruta reservada:** {{ $ruta->nombre_ruta }}  
**Fecha del viaje:** {{ $fechaDisponible->fecha }} <br>
**Cantidad de personas:** {{ $reserva->cantidad_personas }}  

---

**Monto pagado:** S/ {{ number_format($reserva->precio_total - $reserva->saldo, 2) }}  
**Saldo pendiente:** S/ {{ number_format($reserva->saldo, 2) }}  
**Total a pagar:** S/ {{ number_format($reserva->precio_total, 2) }}  

---

**Lugar de embarque:** Intersección de: Av. Manuel Vera Enriquez ⁃ Av. Gerónimo de la Torre  
[Ver ubicación en Maps](https://maps.app.goo.gl/4yjgJo36S7qAqWdS8?g_st=ac)  
**Hora de salida:** 6:00 AM

---

### Indicaciones Generales:
- Llega al menos 15 minutos antes de la hora de salida.
- Lleva tu DNI o pasaporte (Físico o Virtual).  
- No olvides protector solar, ropa cómoda y agua.
- Si tienes acompañantes, asegúrate de compartir esta información.
- Llevar dinero en sencillo por precaución.
- Llevar medicamentos personales.
- Si alguno sufre de mal de altura, tomar una pastilla Gravol 15 min antes de la salida.

---

### Nota importante:
<div style="text-align: center; font-weight: bold; margin-bottom: 15px;">
    Asegúrate de recibir todas las recomendaciones y detalles de esta aventura al 961358621
</div>

<div style="text-align: center; margin: 20px 0;">
    <a href="https://wa.me/51961358621" 
       style="background-color: red; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">
        Escríbenos por WhatsApp
    </a>
</div>

---

### Términos y Condiciones:
Puedes revisar nuestros [Términos y Condiciones](https://n9.cl/1bkel) haciendo clic en el enlace. 

<!-- Botón rojo centrado -->
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ config('app.url') }}" 
       style="background-color: red; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">
        Ver más en nuestro sitio
    </a>
</div>

<!-- Texto final centrado y en negrita -->
<div style="text-align: center; font-weight: bold; margin-top: 20px;">
    Gracias por confiar en el equipo Outdoor Expeditions
</div>

@endcomponent
