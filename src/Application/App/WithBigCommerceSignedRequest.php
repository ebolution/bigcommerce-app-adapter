<?php

namespace Ebolution\BigcommerceAppAdapter\Application\App;

use Ebolution\BigcommerceAppAdapter\Application\Contracts\ConfigurationInterface;
use Ebolution\BigcommerceAppAdapter\Application\Exceptions\InvalidJWTToken;
use Ebolution\BigcommerceAppAdapter\Application\Helpers\JWTHelper;

abstract class WithBigCommerceSignedRequest
{
    public function __construct(
        private readonly ConfigurationInterface $configuration,
    ) {}

    public function __invoke(array $data): array
    {
        $signedPayloadJWT = $data['signed_payload_jwt'];
        if (empty($signedPayloadJWT)) {
            return [
                'result' => 'error',
                'error_message' => 'The signed request from BigCommerce was empty.'
            ];
        }

        try {
            $decoder = new JWTHelper($signedPayloadJWT, $this->configuration->get("AppSecret"));
            $verifiedSignedRequestData = $decoder->decode();
        } catch (InvalidJWTToken) {
            return [
                'result' => 'error',
                'error_message' => 'The signed request from BigCommerce could not be validated'
            ];
        }

        return $this->handle($verifiedSignedRequestData);
    }

    protected abstract function handle(array $verifiedSignedRequestData): array;
}
