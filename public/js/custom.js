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

    // contact add validation
    $('#contact-add, #contact-edit')
        .on('init.field.fv', function(e, data) {
            var $icon      = data.element.data('fv.icon'),
                options    = data.fv.getOptions(),
                validators = data.fv.getOptions(data.field).validators; 

            if (validators.notEmpty && options.icon && options.icon.required) {
                $icon.addClass(options.icon.required).show();
            }
        })
        .find('[name="categories[]"]')
            .select2()
            // Revalidate the color when it is changed
            .change(function(e) {
                $('#contact-add, #contact-edit')
                    .formValidation('revalidateField', 'categories[]');
            })
            .end()
        .formValidation({
            excluded: ':disabled',
            framework: 'bootstrap',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            locale: 'hu_HU',
            fields: {
                contact_name: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 3,
                            max: 100
                        },
                    }
                },
                contact_email: {
                    validators: {
                        notEmpty: {
                        },
                        emailAddress: {
                        },
                    }
                },
                contact_cell: {
                    validators: {
                        regexp: {
                            regexp: /^(36)(20|30|31|70){1}([1-9]{1})([0-9]{6})$/
                        }
                    }
                },
                'categories[]': {
                    validators: {
                        notEmpty: {
                        },
                        callback: {
                            message: 'Legalább egy kategória szükséges',
                            callback: function(value, validator, $field) {
                                // Get the selected options
                                var options = validator
                                    .getFieldElements('categories')
                                    .val();
                                console.log('asd');
                                return (options != null && options.length >= 1);
                            }
                        }
                    }
                },
            }
        })
    ;
});