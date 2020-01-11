$(document).on('click', '.number-spinner button', function () {
    var btn = $(this),
        input = btn.closest('.number-spinner').find('input'),
        oldValue = input.val().trim(),
        newVal = 0;

    if (btn.attr('data-dir') == 'up') {
        newVal = parseInt(oldValue, 10) + 1;
    } else {
        if (oldValue > 0) {
            newVal = parseInt(oldValue, 10) - 1;
        } else {
            newVal = 0;
        }
    }
    input.val(newVal);
    calc();
});

$(document).on('change', 'div.form-group select, div.form-group input', calc);

function calc() {
    var total = 0.0,
        deposit = 0.0;
    var adults = parseInt($("#adults").val(), 10),
        yas = parseInt($("#yas").val(), 10),
        jrsrs = parseInt($("#jrsrs").val(), 10),
        children = parseInt($("#children").val(), 10),
        babies = parseInt($("#babies").val(), 10);
    var singlealert = $("#single-alert"),
        adultalert = $("#adult-choose"),
        yaalert = $("#ya-choose"),
        adultsfee = $("#adults-fee"),
        yasfee = $("#yas-fee"),
        childrenfee = $("#children-fee");
    switch (adults + yas + jrsrs + children + babies) {
        case 0:
            break;
        case 1:
            deposit = 200.0;
            break;
        default:
            deposit = 400.0;
    }
    singlealert.hide();
    adultalert.hide();
    yaalert.hide();
    switch (parseInt($("#adults-housing").val(), 10)) {
        case 0:
            adultsfee.html("$0.00");
            childrenfee.html("$0.00");
            if (adults > 0) {
                adultalert.show();
            }
            break;
        case 1:
            switch (adults + children + babies) {
                case 1:
                    rate = adults * guestsuite[0] * 6;
                    singlealert.show();
                    break;
                case 2:
                    rate = adults * guestsuite[1] * 6;
                    break;
                case 3:
                    rate = adults * guestsuite[2] * 6;
                    break;
                default:
                    rate = adults * guestsuite[3] * 6;
            }
            total += rate + (children * guestsuite[4] * 6);
            adultsfee.html("$" + rate.toFixed(2));
            childrenfee.html("$" + (children * guestsuite[4] * 6).toFixed(2));
            break;
        case 3:
            total += adults * lakewood[0] * 6 + children * lakewood[2] * 6;
            adultsfee.html("$" + (adults * lakewood[0] * 6).toFixed(2));
            childrenfee.html("$" + (children * lakewood[2] * 6).toFixed(2));
            break;
        case 4:
            total += adults * tentcamp[0] * 6 + children * tentcamp[2] * 6;
            adultsfee.html("$" + (adults * tentcamp[0] * 6).toFixed(2));
            childrenfee.html("$" + (children * tentcamp[2] * 6).toFixed(2));
            break;
    }
    switch (parseInt($("#yas-housing").val(), 10)) {
        case 0:
            yasfee.html("$0.00");
            if (yas > 0) {
                yaalert.show();
            }
            break;
        case 1:
            total += yas * lakewood[6] * 6;
            yasfee.html("$" + (yas * lakewood[6] * 6).toFixed(2));
            break;
        case 2:
            total += yas * tentcamp[6] * 6;
            yasfee.html("$" + (yas * tentcamp[6] * 6).toFixed(2));
            break;
    }
    total += jrsrs * lakewood[1] * 6;
    $("#jrsrs-fee").html("$" + (jrsrs * lakewood[1] * 6).toFixed(2));
    total += babies * guestsuite[6] * 6;
    $("#babies-fee").html("$" + (babies * guestsuite[6] * 6).toFixed(2));
    $("#deposit").html("$" + Math.min(total, deposit).toFixed(2));
    $("#arrival").html("$" + Math.max(total - deposit, 0).toFixed(2));
    $("#total").html("$" + total.toFixed(2));
}

calc();
