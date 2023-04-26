<?php

namespace Ebolution\BigcommerceAppAdapter\Application\Helpers;

use Ebolution\BigcommerceAppAdapter\Application\Exceptions\InvalidJWTToken;

class JWTDecoder
{
    public function __construct(
        private string $signedRequest,
        private string $encriptionKey
    ) {}

    /**
     * @throws InvalidJWTToken
     */
    public function decode()
    {
        list($encodedData, $encodedSignature) = explode('.', $this->signedRequest, 2);

        // decode the data
        $signature = base64_decode($encodedSignature);
        $jsonStr = base64_decode($encodedData);
        $data = json_decode($jsonStr, true);

        // confirm the signature
        $expectedSignature = hash_hmac('sha256', $jsonStr, $this->encriptionKey, $raw = false);
        if (!hash_equals($expectedSignature, $signature)) {
            error_log('Bad signed request from BigCommerce!');
            throw new InvalidJWTToken('Request signature is invalid');
        }
        return $data;
    }
}
