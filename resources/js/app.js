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
    var forms = $('form#login, form#create');
    forms.find('input.is-invalid').removeClass("is-invalid");

    if ($('input#email_login').val() && $('input#password_login').val()) {
        $('form#login').submit();
        return;
    }
    if ($('input#email_create').val() && $('input#password_create').val() && $('input#confirm_create').val()) {
        $('form#create').submit();
        return;
    }
    $(this).removeClass('btn-primary').addClass('btn-danger');
    forms.find('input[required]').filter(function () {
        return !this.value;
    }).addClass("is-invalid");
});

var steps = $("ul#littlesteps");
var toast = $("div.toast");
if (steps.length > 0 || toast.length === 1) {
    $.getJSON("/data/steps", function (data) {
        var message = "Your registration is complete! See you \"next week\"!";
        var link = "#";
        var icon = "fa-check";
        if (data[5] === true) {
            $("#nametag-success").toggleClass('d-none');
        } else {
            message = "You're registered, but you can customize your nametag(s) by clicking here."
            link = "/nametag";
            icon = "fa-id-card";
        }
        if (data[6] === true) {
            $("#medical-success").toggleClass('d-none');
        } else {
            message = "You're registered, but please sign your medical release forms by clicking here."
            link = "/medical";
            icon = "fa-envelope"
        }
        if (data[3] === true) {
            $("#workshop-success").toggleClass('d-none');
        } else {
            message = "You're registered, but you may want to sign up for workshops by clicking here."
            link = "/workshopchoice";
            icon = "fa-rocket";
        }
        if (data[4] === true) {
            $("#room-success").toggleClass('d-none');
        } else {
            message = "You're registered; next you'll want to choose a room by clicking here."
            link = "/roomselection";
            icon = "fa-bed";
        }
        if (data[8] !== false) {
            message = "You're registered; check back " + data[8] + " to choose workshops and select your room."
            link = "#";
            icon = "fa-fire";
        }
        if (data[0] === true) {
            $("#household-success").toggleClass('d-none');
        } else {
            message = "You're registered but we need you to update your billing information here."
            link = "/household";
            icon = "fa-home";
        }
        if (data[2] === true) {
            $("#payment-success").toggleClass('d-none');
        } else {
            message = "You are not yet registered for this year, please pay your deposit by clicking here."
            link = "/payment";
            icon = "fa-usd-square";
        }
        if (data[1] === true) {
            $("#camper-success").toggleClass('d-none');
        } else {
            message = "You are not yet registered for this year: let's get started by clicking here."
            link = "/campers";
            icon = "fa-users";
        }
        if (toast.length === 1) {
            $("a#toast-link").prop("href", link);
            $("#toast-icon").toggleClass("fa-check").toggleClass(icon);
            $("#welcomeback").text("Welcome back, " + data[7] + "!");
            toast.find('div.toast-body').html(message);
            toast.toast('show');
        }
    });
}

function replaceCamperMarkup(data, term) {
    return data.replace(new RegExp(term, "i"), "<strong>$&</strong>");
}

function templateRescamp(data) {
    if (!data.id) return data.text;
    var message = replaceCamperMarkup(data.firstname, data.term) + ' ' + replaceCamperMarkup(data.lastname, data.term);
    if (data.email) message += ' &lt;' + replaceCamperMarkup(data.email, data.term) + '&gt;';
    return message;
}

function templateSelcamp(data) {
    if (!data.id) return data.text;
    var cs = $('li#campersearch');
    cs.find('button.disabled').toggleClass('disabled');
    cs.find('a').each(function () {
        if (!$(this).prop('href').includes(data.id)) {
            $(this).prop('href', $(this).prop('href') + "/" + data.id);
        }
    });
    return data.firstname + " " + data.lastname;
}

$("select#camperlist").select2({
    ajax: {
        url: '/data/camperlist',
        dataType: 'json',
        quietMillis: 250,
        processResults: function (data) {
            return {
                results: data
            };
        }
    },
    escapeMarkup: function (markup) {
        return markup;
    },
    minimumInputLength: 3,
    placeholder: 'Camper search...',
    templateResult: templateRescamp,
    templateSelection: templateSelcamp,
    theme: 'bootstrap4',
});


$('.dropdown-submenu a.sub').on("click", function (e) {
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
});

