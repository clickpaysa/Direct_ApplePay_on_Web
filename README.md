# Direct_ApplePay_on_Web
Clickpay - ApplePay Web SDK (JS) sample code

## Setup

1. You must have a Apple Developer Account To use this Feature
2. The Domain URL which you want to display the ApplePay Button. It should be verfied under same Apple Developer Account and under same merchant ID which you will create Certificates

![image](https://github.com/clickpaysa/Direct_ApplePay_on_Web/assets/135695828/2b6c16ba-58b3-44ed-a690-dfeb7762b9cb)
 
3. Create two certificates (Apple Pay Payment Processing Certificate & Apple Pay Merchant Identity Certificate)
4. Create Apple Pay Payment Processing Certificate using below Link

       https://support.clickpay.com.sa/en/support/solutions/articles/73000593115-how-to-configure-apple-pay-certificate-in-my-clickpay-dashboard-
5. Create Apple Pay Merchant Identity Certificate using below Steps

       openssl req -sha256 -nodes -newkey rsa:2048 -keyout merchant-cert.key -out merchant-cert.csr   (Create the CSR and Key File)

       Upload the CSR in Apple Developer portal to create merchant identifier
       Once Created Download the Certificate and Convert the downloaded cer to crt using below command

       openssl x509 -inform der -in merchant_id.cer -out merchant-cert.crt
   
6. Upload ApplePay certificate file `(merchant-cert.crt)` & Key `(merchant-cert.key)` to `cert` folder
7. Update `env.php` file with `certificates` names

       'apple_merchant_id' => 'certificate Identifier'   //You can find this in Apple developer Portal
   
       'apple_ssl_cert_file' => '/cert/merchant-cert.crt',
       'apple_cert_key_file' => '/cert/merchant-cert.key', 
   
8. Add your API keys to `env.php` file

9. Add your Domain URL to `applepay.php` file

       'initiativeContext' => "Domain URL",  // Which you Vertified In your Apple devloper Account
   
10. Configure the `currency` & `amount` in `_config.php`

## Debug

Debug records saved to `debug_clickpay.log`

## References:

1. https://applepaydemo.apple.com/
2. https://developer.apple.com/documentation/apple_pay_on_the_web/configuring_your_environment
