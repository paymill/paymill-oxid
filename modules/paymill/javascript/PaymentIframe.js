var paymillInit = function() {
    var options = {
        labels: {
            number: PAYMILL_TRANSLATION_LABELS.PAYMILL_card_number_label,
            cvc: PAYMILL_TRANSLATION_LABELS.PAYMILL_card_cvc_label,
            cardholder: PAYMILL_TRANSLATION_LABELS.PAYMILL_card_holdername_label,
            exp: PAYMILL_TRANSLATION_LABELS.PAYMILL_card_expiry_label
        },
        placeholders: {
            number: 'XXXX XXXX XXXX XXXX',
            cvc: 'XXX',
            cardholder: 'John Doe',
            exp_month: 'MM',
            exp_year: 'YYYY'
        },
        errors: {
            number: PAYMILL_TRANSLATION.PAYMILL_VALIDATION_CARDNUMBER,
            cvc: PAYMILL_TRANSLATION.PAYMILL_VALIDATION_CVC,
            exp: PAYMILL_TRANSLATION.PAYMILL_VALIDATION_EXP
        }
    };

    if (PAYMILL_COMPLIANCE_CSS) {
        options.stylesheet = PAYMILL_COMPLIANCE_CSS;
    }

    paymill.embedFrame('payment-form-cc', options, function(error) {
        if (error && PAYMILL_DEBUG === "1") {
            console.log(error.apierror, error.message);
        } else {
            // Frame was loaded successfully and is ready to be used.
        }
    });

    $('#payment').submit(function (event) {
        var cc = $('#payment_paymill_cc').attr('checked');

        if (cc && PAYMILL_COMPLIANCE) {
            // prevent form submit
            event.preventDefault();

            // disable submit-button to prevent multiple clicks
            $('#paymentNextStepBottom').attr("disabled", "disabled");

            paymill.createTokenViaFrame({
                amount_int: PAYMILL_AMOUNT,
                currency: PAYMILL_CURRENCY
            }, function(error, result) {
                // Handle error or process result.
                if (error && PAYMILL_DEBUG === "1") {
                    // Token could not be created, check error.apierror for reason.
                    console.log(error.apierror, error.message);
                } else {
                    // Token was created successfully and can be sent to backend.
                    console.log(result.token);
                                // add token into hidden input field for request to the server
                    $("#payment").append("<input type='hidden' name='paymillToken' value='" + result.token + "'/>");
                    $("#payment").get(0).submit();
                }
            });
        }

        return true;
    });

    function paymillDebug(message)
    {
        if (PAYMILL_DEBUG === "1") {
            console.log(message);
        }
    }
}

if (window.addEventListener){
    window.addEventListener("load", paymillInit);
} else if (window.attachEvent){
    window.attachEvent("onload", paymillInit);
} else window.onload = paymillInit;