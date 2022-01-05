$( document ).ready(function() {
    $('#add-image').click(function() {
        // Récupère le nombre de div 'ad_images'
        const index = +$('#widgets-counter').val();

        // Récupère le prototype des 'entries' et l'édite avec le chiffre voulu 
        const template = $('#ad_images').data('prototype').replace(/__name__/g, index);

        // Injecte le code du prototype modifié dans la div « ad_images »
        $('#ad_images').append(template);

        // Incrémente la valeur pour comptage du nombre de div 'ad_images'
        $('#widgets-counter').val(index + 1);

        handleDeleteButton();
    });

    function handleDeleteButton() {
        $('button[data-action="delete"]').click(function() {
            const target = this.dataset.target;
            $(target).remove();
        });
    }

    function updateCounter() {
        // Récupère le nombre d'image dejà présente
        // (nombre de div « form-group » dans la div « ad-images »)
        const count = +$('#ad_images div.form-group').length;

        // Met le compteur à jour
        $('#widgets-counter').val(count);
    }

    handleDeleteButton();
    updateCounter();
});