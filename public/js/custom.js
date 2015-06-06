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
        title: _('Additional information')
    });
    
    // bootstrap enchanced confrimation
    $('.confirm-del').confirmation({
        title: _('Biztos vagy benne?'),
        href: $(this).attr('href'),
        btnOkLabel: _('Igen'),
        btnCancelLabel: _('Nem'),
        container: 'body'
    });

    // select2
    $('.select2').select2({
        placeholder: _("Click here (and start typing)"),
        language: "all",
    });
});