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
**Lugar de embarque:** Avenida los Pinos, San Juan de Miraflores  
[Ver ubicación en Maps](https://maps.app.goo.gl/NMhxhmYQAbFJbAoB7)  
**Hora de salida:** 6:00 AM


---
### Indicaciones importantes:
- Llega al menos 15 minutos antes de la hora de salida.
- Lleva tu documento de identidad.
- No olvides protector solar, ropa cómoda y agua.
- Si tienes acompañantes, asegúrate de compartir esta información.

@component('mail::button', ['url' => config('app.url')])
Ver más en nuestro sitio
@endcomponent

Gracias por reservar con nosotros.  
Outdoor Expeditions
@endcomponent
