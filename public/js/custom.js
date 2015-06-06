$(document).ready( function() {
    // popover init
    $('.additional-popover').popover({
        container: 'body',
        trigger: 'click',
        html: 'true',
        placement: 'bottom',
        content: function() {

            return $(this).find('.additional-data').html();
        },
        title: 'Additional information'
    });
    
    // bootstrap enchanced confrimation
    $('.confirm-del').confirmation({
        title: 'Biztos vagy benne?',
        href: $(this).attr('href'),
        btnOkLabel: 'Igen',
        btnCancelLabel: 'Nem',
        container: 'body'
    });

    // select2
    $('.select2').select2({
        placeholder: "Kattints ide (és kezdj el gépelni)",
        language: "all",
    });
});