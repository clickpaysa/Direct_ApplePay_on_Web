<?php
require_once './_config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApplePay JS</title>

    <script src="https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js"></script>
    <style>
        apple-pay-button {
            --apple-pay-button-width: 150px;
            --apple-pay-button-height: 30px;
            --apple-pay-button-border-radius: 3px;
            --apple-pay-button-padding: 0px 0px;
            --apple-pay-button-box-sizing: border-box;
        }
    </style>
</head>

<body>

    <div>
        <h3>Hi ApplePay</h3>
    </div>
    <div>
        <apple-pay-button buttonstyle="black" type="plain" locale="en" onclick="onApplePayButtonClicked()"></apple-pay-button>
    </div>
    <div>
        <span id="pnl_log"></span>
    </div>

    <script type="text/javascript">
        function onApplePayButtonClicked() {

            if (!ApplePaySession) {
                _show_log("ApplePay Session not found");
                return;
            }

            // Define ApplePayPaymentRequest
            const request = {
                "countryCode": "<?= $country_code ?>",
                "currencyCode": "<?= $ap_currency ?>",
                "merchantCapabilities": [
                    "supports3DS"
                ],
                "supportedNetworks": [
                    "visa",
                    "mada",
                    "masterCard",
                    "amex"
                ],
                "total": {
                    "label": "Click Pay Riyas Demo",
                    "type": "final",
                    "amount": "<?= $ap_amount ?>"
                }
            };

            // Create ApplePaySession
            const session = new ApplePaySession(14, request);

            session.onvalidatemerchant = async event => {
                // Call your own server to request a new merchant session.
                // const merchantSession = await validateMerchant();
                // session.completeMerchantValidation(merchantSession);

                _show_log("on validate merchant begin");

                // console.log(event, event.validationURL);

                fetch('applepay.php?vurl=' + event.validationURL)
                    .then(res => res.json()) // Parse response as JSON.
                    .then(merchantSession => {
                        session.completeMerchantValidation(merchantSession);
                        _show_log("on validate merchant complete");
                    })
                    .catch(err => {
                        _show_log("Error fetching merchant session", err);
                    });

                _show_log("on validate merchant waiting");
            };

            session.onpaymentmethodselected = event => {
                _show_log("on payment method selected begin");

                // Define ApplePayPaymentMethodUpdate based on the selected payment method.
                // No updates or errors are needed, pass an empty object.
                const update = {
                    "newTotal": {
                        "label": "Click Pay Riyas Demo",
                        "type": "final",
                        "amount": "<?= $ap_amount ?>"
                    }
                };
                session.completePaymentMethodSelection(update);

                _show_log("on paymentmethod selected complete");
            };

            session.onshippingmethodselected = event => {
                _show_log("on shippingmethod selected begin");

                // Define ApplePayShippingMethodUpdate based on the selected shipping method.
                // No updates or errors are needed, pass an empty object. 
                const update = {};
                session.completeShippingMethodSelection(update);

                _show_log("on shipping method selected complete");
            };

            session.onshippingcontactselected = event => {
                _show_log("on shipping contact selected begin");

                // Define ApplePayShippingContactUpdate based on the selected shipping contact.
                const update = {};
                session.completeShippingContactSelection(update);

                _show_log("on shipping contact selected complete");
            };

            session.onpaymentauthorized = event => {
                _show_log("on payment authorized begin");

                let paymentToken = event.payment.token;

                fetch("applepay_payment.php", {
                        method: "POST",
                        body: JSON.stringify(paymentToken),
                    })
                    .then(res => res.json())
                    .then(res => {
                        console.log(res);

                        let success = res.success;
                        _show_log(res.result.message);

                        // Define ApplePayPaymentAuthorizationResult
                        const result = {
                            "status": success ? ApplePaySession.STATUS_SUCCESS : ApplePaySession.STATUS_FAILURE
                        };
                        session.completePayment(result);

                        _show_log("on payment authorized complete");
                    })
                    .catch(err => {
                        _show_log("Error authorizing the payment");
                    });

                _show_log("on payment authorized waiting");
            };


            session.oncancel = event => {
                // Payment cancelled by WebKit
                _show_log("on cancel complete");
            };

            session.begin();
            _show_log("begin");
        }

        //

        function _show_log(msg, error) {
            if (error) {
                console.error(msg, error);
            } else {
                console.log(msg);
            }
            document.getElementById('pnl_log').innerText += "\n" + msg;
        }
    </script>
</body>

</html>