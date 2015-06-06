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

    // bootstrap checkbox switch
    var onText = '<span class="fa fa-check fa-lg" aria-hidden="true"></span>',
        offText = '<span class="fa fa-close fa-lg" aria-hidden="true"></span>';
    $("input[type=\"checkbox\"], input[type=\"radio\"]")
        .not("[data-switch-no-init]")
        .bootstrapSwitch('size', 'small');
    $("input[type=\"checkbox\"], input[type=\"radio\"]")
        .not("[data-switch-no-init]")
        .bootstrapSwitch('onText', onText);
    $("input[type=\"checkbox\"], input[type=\"radio\"]")
        .not("[data-switch-no-init]")
        .bootstrapSwitch('offText', offText);
    
    // bootstrap enchanced confrimation
    $('.confirm-del').confirmation({
        title: _('Are you sure?'),
        href: $(this).attr('href'),
        btnOkLabel: _('Yes'),
        btnCancelLabel: _('No'),
        container: 'body'
    });

    // select2
    $('.select2').select2({
        placeholder: _("Click here (and start typing)"),
        language: "all",
    });

    // zeushost-add
    $('#zeushost-add, #zeushost-edit')
        .on('init.field.fv', function(e, data) {
            // data.fv      --> The FormValidation instance
            // data.field   --> The field name
            // data.element --> The field element

            var $icon      = data.element.data('fv.icon'),
                options    = data.fv.getOptions(),                      // Entire options
                validators = data.fv.getOptions(data.field).validators; // The field validators

            if (validators.notEmpty && options.icon && options.icon.required) {
                // The field uses notEmpty validator
                // Add required icon
                $icon.addClass(options.icon.required).show();
            }
        })
        .formValidation({
            framework: 'bootstrap',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            locale: 'hu_HU',
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 6,
                            max: 30
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9.-]+$/
                        }
                    }
                },
            }
        })
    ;

    // zeususer-login
    $('#zeususer-login')
        .on('init.field.fv', function(e, data) {
            // data.fv      --> The FormValidation instance
            // data.field   --> The field name
            // data.element --> The field element

            var $icon      = data.element.data('fv.icon'),
                options    = data.fv.getOptions(),                      // Entire options
                validators = data.fv.getOptions(data.field).validators; // The field validators

            if (validators.notEmpty && options.icon && options.icon.required) {
                // The field uses notEmpty validator
                // Add required icon
                $icon.addClass(options.icon.required).show();
            }
        })
        .formValidation({
            framework: 'bootstrap',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            locale: 'hu_HU',
            fields: {
                username: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 3,
                            max: 16
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9.-]+$/
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 6
                        }
                    }
                }
            }
        })
    ;

    // zeususer-change-password
    $('#zeususer-change-password')
        .on('init.field.fv', function(e, data) {
            var $icon      = data.element.data('fv.icon'),
                options    = data.fv.getOptions(),
                validators = data.fv.getOptions(data.field).validators;

            if (validators.notEmpty && options.icon && options.icon.required) {
                $icon.addClass(options.icon.required).show();
            }
        })
        .formValidation({
            framework: 'bootstrap',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            locale: 'hu_HU',
            fields: {
                current_password: {
                    validators: {
                        notEmpty: {},
                        stringLength: {
                            min: 6,
                        }
                    }
                },
                new_password: {
                    validators: {
                        identical: {
                            field: 'new_password_again',
                        },
                        notEmpty: {},
                        stringLength: {
                            min: 6,
                        }
                    }
                },
                new_password_again: {
                    validators: {
                        identical: {
                            field: 'new_password',
                        },
                        notEmpty: {},
                        stringLength: {
                            min: 6,
                        }
                    }
                },
            }
        })
    ;

    $('#zeususer-add')
        .on('init.field.fv', function(e, data) {
            var $icon      = data.element.data('fv.icon'),
                options    = data.fv.getOptions(),
                validators = data.fv.getOptions(data.field).validators; 

            if (validators.notEmpty && options.icon && options.icon.required) {
                $icon.addClass(options.icon.required).show();
            }
        })
        .formValidation({
            framework: 'bootstrap',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            locale: 'hu_HU',
            fields: {
                username: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 3,
                            max: 16
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9.-]+$/
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 6,
                            max: 255
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9.-]+$/
                        }
                    }
                },
                email: {
                    validators: {
                        emailAddress: {
                        },
                    }
                }
            }
        })
    ;

    $('#zeususer-edit')
        .on('init.field.fv', function(e, data) {
            var $icon      = data.element.data('fv.icon'),
                options    = data.fv.getOptions(),
                validators = data.fv.getOptions(data.field).validators;

            if (data.fv.getOptions(data.field).enabled 
                && validators.notEmpty 
                && options.icon 
                && options.icon.required) 
            {
                $icon.addClass(options.icon.required).show();
            }
        })
        .on('keyup', '[name="password"]', function(e) {
            var password = $('#zeususer-edit').find('[name="password"]').val(),
                fv = $('#zeususer-edit').data('formValidation');
            if (password !== '') {
                fv
                .enableFieldValidators('password', true)
                .revalidateField('password');
            } else {
                fv
                .enableFieldValidators('password', false);            
            }
        })
        .formValidation({
            framework: 'bootstrap',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            locale: 'hu_HU',
            fields: {
                username: {
                    enabled: true,
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 3,
                            max: 16
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9.-]+$/
                        }
                    }
                },
                password: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 6,
                            max: 255
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9.-]+$/
                        }
                    }
                },
                email: {
                    validators: {
                        emailAddress: {
                        },
                    }
                }
            }
        })
    ;

    $('#zeusaccess-role-add, #zeusaccess-role-edit')
        .on('init.field.fv', function(e, data) {
            var $icon      = data.element.data('fv.icon'),
                options    = data.fv.getOptions(),
                validators = data.fv.getOptions(data.field).validators; 

            if (validators.notEmpty && options.icon && options.icon.required) {
                $icon.addClass(options.icon.required).show();
            }
        })
        .formValidation({
            framework: 'bootstrap',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            locale: 'hu_HU',
            fields: {
                role: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 3,
                            max: 20
                        },
                        regexp: {
                            regexp: /^[a-z0-9]+$/
                        }
                    }
                },
                name: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 5,
                            max: 255
                        }
                    }
                },
            }
        })
    ;

    // translation
    function _(key){
        if (typeof admin !== 'undefined' && admin.I18n[key] ) {
            return admin.I18n[key];
        } else {
            return key;
        }
    }
});