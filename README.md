# Google's Simple reCAPTCHA Client

Google's simple reCAPTCHA client, this client is just a refactored version of [Google's PHP reCAPTCHA lib](https://github.com/google/ReCAPTCHA/blob/master/php/recaptchalib.php).

## Usage

```php

use Nebiros\GoogleSimpleRecaptchaClient\Client as RecaptchaClient;

$recaptchaClient = new RecaptchaClient($recaptchaSecret);
$resp = $recaptchaClient->verifyResponse(
	$_SERVER["REMOTE_ADDR"],
	$_POST["g-recaptcha-response"]
);

```
