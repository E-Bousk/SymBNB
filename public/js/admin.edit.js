$( document ).ready(function() {
    $('[data-action="delete"]').on('click', function() {
        const target = $(this).data('target');
        $(target).remove();
    });
});