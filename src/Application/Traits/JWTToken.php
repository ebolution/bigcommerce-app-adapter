<?php

namespace Ebolution\BigcommerceAppAdapter\Application\Traits;

use Ebolution\BigcommerceAppAdapter\Application\Helpers\JWTHelper;

trait JWTToken
{
    public function buildToken(array $data): string
    {
        $payload = $this->payload($data);
        $encoder = new JWTHelper($payload, $this->configuration->get("AppSecret"), true);
        $jwt_token = $encoder->encode();

        return $jwt_token;
    }

    private function payload(array $data): array
    {
        $hours = $this->configuration->get("token_duration_hours");
        $payload = [
            'iss' => $this->configuration->get("AppName"),      // Issuer
            'sub' => 'BigCommerce',                             // Subject
            'iat' => time(),                                    // Issued at
            'exp' => strtotime("+{$hours} hours"),      // Expires at
        ];

        return array_merge($data, $payload);
    }
}
