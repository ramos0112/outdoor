document.addEventListener('DOMContentLoaded', function () {
    const openRentModal = document.getElementById('openRentModal');
    const modalBackdrop = document.getElementById('rentacarsModalBackdrop');
    const closeRentModal = document.getElementById('closeRentModal');
    const rentForm = document.getElementById('rentacarsForm');
    const whatsappConfig = document.getElementById('rentacarsWhatsappConfig');

    if (!openRentModal || !modalBackdrop || !closeRentModal || !rentForm) {
        return;
    }

    const whatsappNumber = whatsappConfig ? whatsappConfig.dataset.whatsapp : '';

    openRentModal.addEventListener('click', function () {
        modalBackdrop.classList.remove('hidden');
    });

    closeRentModal.addEventListener('click', function () {
        modalBackdrop.classList.add('hidden');
    });

    modalBackdrop.addEventListener('click', function (event) {
        if (event.target === modalBackdrop) {
            modalBackdrop.classList.add('hidden');
        }
    });

    rentForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const vehicleName = openRentModal.dataset.vehicle;
        const customerName = document.getElementById('customerName').value.trim();
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const destination = document.getElementById('destination').value.trim();

        if (!customerName || !startDate || !endDate || !destination) {
            alert('Por favor completa todos los campos antes de enviar la solicitud.');
            return;
        }

        const mensaje = `Hola, quisiera solicitar disponibilidad de *${vehicleName}*.%0A%0A` +
            `Nombre: ${customerName}%0A` +
            `Fecha de inicio: ${startDate}%0A` +
            `Fecha de fin: ${endDate}%0A` +
            `Destino: ${destination}%0A%0A` +
            `Gracias.`;

        const url = whatsappNumber
            ? `https://wa.me/${whatsappNumber}?text=${mensaje}`
            : `https://wa.me/?text=${mensaje}`;

        window.open(url, '_blank');
        modalBackdrop.classList.add('hidden');
    });
});
