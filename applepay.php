<?php

require_once './_config.php';

//

$validation_url = filter_input(INPUT_GET, 'vurl');
if (!$validation_url) {
    die('No vURL');
}

$applepay_url = $validation_url;
$applepay_data = [
    'merchantIdentifier' => $env['apple_merchant_id'],
    'displayName' => "PT Integrations Team",
    'initiative' => "web",
    'initiativeContext' => "test.sandboxurpay.com",
];

$result = sendRequest($applepay_url, $applepay_data);
echo $result;

// ClickpayHelper::log($result, 1);

//

function sendRequest($request_url, $values)
{
    global $env;

    $headers = [
        'Content-Type: application/json',
    ];

    $post_params = json_encode($values);


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    curl_setopt($ch, CURLOPT_SSLCERT, __DIR__ . $env['apple_ssl_cert_file']);
    curl_setopt($ch, CURLOPT_SSLKEY, __DIR__ . $env['apple_cert_key_file']);

    // curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $result = curl_exec($ch);

    $error_num = curl_errno($ch);
    if ($error_num) {
        $error_msg = curl_error($ch);
        echo ("Error response [($error_num) $error_msg], [$result]");
    }

    curl_close($ch);

    return $result;
}
