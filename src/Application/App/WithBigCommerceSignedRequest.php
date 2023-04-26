<?php

namespace Ebolution\BigcommerceAppAdapter\Application\App;

use Ebolution\BigcommerceAppAdapter\Application\Contracts\ConfigurationInterface;
use Ebolution\BigcommerceAppAdapter\Application\Exceptions\InvalidJWTToken;
use Ebolution\BigcommerceAppAdapter\Application\Helpers\JWTDecoder;

abstract class WithBigCommerceSignedRequest
{
    public function __construct(
        private readonly ConfigurationInterface $configuration,
    ) {}

    public function __invoke(array $data): array
    {
        $signedPayload = $data['signed_payload'];
        if (empty($signedPayload)) {
            return [
                'result' => 'error',
                'error_message' => 'The signed request from BigCommerce was empty.'
            ];
        }

        try {
            $decoder = new JWTDecoder($signedPayload, $this->configuration->get("AppSecret"));
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
