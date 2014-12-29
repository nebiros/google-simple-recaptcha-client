<?php

namespace Nebiros\GoogleSimpleRecaptchaClient;

use Nebiros\GoogleSimpleRecaptchaClient\Exception;
use Nebiros\GoogleSimpleRecaptchaClient\Response;

class Client {
    const SIGNUP_URL = "https://www.google.com/recaptcha/admin";

    const SITE_VERIFY_URL = "https://www.google.com/recaptcha/api/siteverify";

    const PHP_REQUEST_VERSION = "php_1.0"; 

    protected $_secret;

    /**
     * Constructor.
     *
     * @param string $secret shared secret between site and ReCAPTCHA server.
     */
    public function __construct($secret) {
        if ($secret == null || $secret == "") {
            throw new Exception("To use reCAPTCHA you must get an API key from: " . self::SIGNUP_URL, 1);            
        }

        $this->_secret = $secret;
    }

    /**
     * Submits an HTTP GET to a reCAPTCHA server.
     *
     * @param string $path url path to recaptcha server.
     * @param array  $data array of parameters to be sent.
     *
     * @return array response
     */
    protected function _submitHTTPGet($path, Array $data) {
        $req = http_build_query($data);
        $response = file_get_contents($path . "?" . $req);
        return $response;
    }

    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes
     * CAPTCHA test.
     *
     * @param string $remoteIp   IP address of end user.
     * @param string $response   response string from recaptcha verification.
     *
     * @return Response
     */
    public function verifyResponse($remoteIp, $response) {
        if ($response == null || strlen($response) == 0) {
            $recaptchaResponse = new Response();
            $recaptchaResponse->success = false;
            $recaptchaResponse->errorCodes = "missing-input";
            return $recaptchaResponse;
        }

        $getResponse = $this->_submitHttpGet(
            self::SITE_VERIFY_URL,
            array(
                "secret" => $this->_secret,
                "remoteip" => $remoteIp,
                "v" => self::PHP_REQUEST_VERSION,
                "response" => $response
            )
        );

        $answers = json_decode($getResponse, true);
        $recaptchaResponse = new Response();

        if (trim($answers["success"]) == true) {
            $recaptchaResponse->success = true;
        } else {
            $recaptchaResponse->success = false;
            $recaptchaResponse->errorCodes = $answers["error-codes"];
        }

        return $recaptchaResponse;
    }
}
