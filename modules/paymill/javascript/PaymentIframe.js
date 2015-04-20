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

    paymill.embedFrame('payment-form-cc', options, function(error) {
        if (error && PAYMILL_DEBUG === "1") {
            console.log(error.apierror, error.message);
        } else {
            // Frame was loaded successfully and is ready to be used.
        }
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