$(document).ready(function() {
    $('#booking_startDate, #booking_endDate').datepicker({
        language: 'fr',
        // Récupère les dates indisponibles à la réservation
        datesDisabled : $('#arrayOfDates').data('notAvailableDate'),
        startDate: new Date()
    });
    
    // Appel de la fonction à chaque changement de date dans le calendrier
    $('#booking_startDate, #booking_endDate').on('change', calculateAmount);

    function calculateAmount() {
        // Récupère les dates et les transforme avec une RegEx au format supporté par 'Date' de JS
        // (ex: de « 31/12/2021 » à « 2021-12-31 ») 
        const startDate = new Date($('#booking_startDate').val().replace(/(\d+)\/(\d+)\/(\d{4})/, '$3-$2-$1'));
        const endDate = new Date($('#booking_endDate').val().replace(/(\d+)\/(\d+)\/(\d{4})/, '$3-$2-$1'));

        if (startDate && endDate && startDate < endDate) {
            // Un jour exprimé en millisecondes
            const DAY_TIME = 24 * 60 * 60 * 1000;

            // Transforme les dates en 'TimeStamp' (millisecondes) avec « getTime »
            // et soustrait la date du début à la date de fin
            const interval = endDate.getTime() - startDate.getTime();
            // Résultat exprimé en 'jour'
            const days = interval / DAY_TIME;
            // Calcul du montant total
            const amount =  days * $('#price').data('price')

            // Affiche le nombre de jour et le montant dans le HTML
            $('#days').text(days);
            $('#amount').text(amount.toLocaleString('fr-FR'));
        }
    }
});