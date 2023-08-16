<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Application\Helpers;

use Ebolution\BigcommerceAppAdapter\Application\Exceptions\InvalidJWTToken;

class JWTHelper
{
    public function __construct(
        private readonly string|array $data,
        private readonly string $encryptionKey
    ) {}

    /**
     * @throws InvalidJWTToken
     */
    public function decode()
    {
        list($encodedHeader, $encodedData, $encodedSignature) = explode('.', $this->data, 3);

        // decode the data
        $signature = $this->base64_decode($encodedSignature);
        $jsonStr = $this->base64_decode($encodedData);
        $data = json_decode($jsonStr, true);
        $jsonStr = $this->base64_decode($encodedHeader);
        $header = json_decode($jsonStr, true);

        // confirm the signature
        $expectedSignature = hash_hmac('sha256', $encodedHeader . '.' . $encodedData, $this->encryptionKey, true);
        if (!hash_equals($expectedSignature, $signature)) {
            error_log('Bad signed request from BigCommerce!');
            throw new InvalidJWTToken('Request signature is invalid');
        }
        return $data;
    }

    public function encode(): string
    {
        $header = json_encode($this->defaultHeader());
        $base64UrlHeader = $this->base64_encode($header);

        $payload = json_encode($this->data);
        $base64UrlPayload = $this->base64_encode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $this->encryptionKey, true);
        $base64UrlSignature = $this->base64_encode($signature);

        return $base64UrlHeader . '.' . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public function defaultHeader(): array
    {
        return [
            "alg" => "HS256",
            "typ" => "JWT"
        ];
    }

    function base64_encode($text): string
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }

    function base64_decode($text): string
    {
        return base64_decode(str_replace(
            ['-', '_'],
            ['+', '/'],
            $text
        ));
    }
}
