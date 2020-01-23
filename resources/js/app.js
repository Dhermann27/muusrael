require('./bootstrap');


import '@fortawesome/fontawesome-pro/css/all.css';
import '@fortawesome/fontawesome-pro/js/all.js';
import 'bootstrap-datepicker/js/bootstrap-datepicker.js'
import 'select2/dist/js/select2.min'

/* Template Name: Minton - Bootstrap 4 Landing Page Tamplat
   Author: CoderThemes
   File Description: Main JS file of the template
*/

!function ($) {
    "use strict";

    var Minton = function () {
    };

    Minton.prototype.initStickyMenu = function () {
        $(window).scroll(function () {
            var scroll = $(window).scrollTop();

            if (scroll >= 50) {
                $(".sticky").addClass("nav-sticky");
            } else {
                $(".sticky").removeClass("nav-sticky");
            }
        });
    },

        Minton.prototype.initSmoothLink = function () {
            $('.navbar-nav a').on('click', function (event) {
                var $anchor = $(this);
                $('html, body').stop().animate({
                    scrollTop: $($anchor.attr('href')).offset().top - 0
                }, 1500, 'easeInOutExpo');
                event.preventDefault();
            });
        },

        Minton.prototype.initScrollspy = function () {
            $("#navbarCollapse").scrollspy({
                offset: 20
            });
        },

        Minton.prototype.initContact = function () {
            $('#contact-form').submit(function () {

                var action = $(this).attr('action');

                $("#message").slideUp(750, function () {
                    $('#message').hide();

                    $('#submit')
                        .before('')
                        .attr('disabled', 'disabled');

                    $.post(action, {
                            name: $('#name').val(),
                            email: $('#email').val(),
                            comments: $('#comments').val(),
                        },
                        function (data) {
                            document.getElementById('message').innerHTML = data;
                            $('#message').slideDown('slow');
                            $('#cform img.contact-loader').fadeOut('slow', function () {
                                $(this).remove()
                            });
                            $('#submit').removeAttr('disabled');
                            if (data.match('success') != null) $('#cform').slideUp('slow');
                        }
                    );

                });

                return false;

            });
        },


        Minton.prototype.init = function () {
            this.initStickyMenu();
            this.initSmoothLink();
            this.initScrollspy();
            this.initContact();
        },
        //init
        $.Minton = new Minton, $.Minton.Constructor = Minton
}(window.jQuery),

//initializing
    function ($) {
        "use strict";
        $.Minton.init();
    }(window.jQuery);

$('[data-toggle="tooltip"]').tooltip();

$("input#email_login").blur(function () {
    var email = this.value;
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{1,5})+$/.test(email)) {
        $("div#login-found").collapse('hide');
        $("div#login-searching").collapse('show').on('shown.bs.collapse', function () {
            $.getJSON("/data/loginsearch", {term: email})
                .done(function (data) {
                    $("select#login-campers").empty();
                    $.each(data, function (i, item) {
                        $("select#login-campers").append(new Option(item.firstname + " " + item.lastname, item.id, false, true));
                    });
                    $("div#login-searching").collapse('hide');
                    $("div#login-found").collapse('show');
                }).fail(function (data) {
                $("div#login-searching").collapse('hide');
            })
        });
    } else {
        $("div#login-found").collapse('hide');
        $("div#login-searching").collapse('hide');
    }
});

$("button#selectallcampers").click(function () {
    $("select#login-campers option").prop('selected', 'true');
});

$('button.spinner').click(function () {
    var btn = $(this),
        input = btn.parent().parent().find('input').first(),
        oldValue = input.val().trim(),
        newVal = 0;

    if (btn.attr('data-dir') === 'up') {
        newVal = parseInt(oldValue, 10) + 1;
    } else {
        if (oldValue > 1) {
            newVal = parseInt(oldValue, 10) - 1;
        } else {
            newVal = 1;
        }
    }
    input.val(newVal);
});

$('button#begin_reg').click(function () {

    $(this).removeClass('btn-danger').addClass('btn-primary');
    $('form#login, form#create').find('input.is-invalid').removeClass("is-invalid");

    if ($('input#email_login').val() && $('input#password_login').val()) {
        $('form#login').submit();
        return;
    }
    if ($('input#email_create').val() && $('input#password_create').val() && $('input#confirm_create').val()) {
        $('form#create').submit();
        return;
    }
    $(this).removeClass('btn-primary').addClass('btn-danger');
    $('form#login, form#create').find('input[required]').filter(function () {
        return !this.value;
    }).addClass("is-invalid");
});
