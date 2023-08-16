<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Application\App;

use Ebolution\BigcommerceAppAdapter\Application\Contracts\ConfigurationInterface;
use Ebolution\BigcommerceAppAdapter\Domain\Contracts\BCAuthorizedUserRepositoryContract;

final class ErrorUseCase
{
    public function __construct(
        private readonly ConfigurationInterface $configuration
    ) {}

    public function __invoke(array $data): array
    {
        $errorMessage = "Internal Application Error";

        if (array_key_exists('error_message', $data)) {
            $errorMessage = $data['error_message'];
        }

        return [
            'result' => 'view',
            'view' => 'error',
            'data' => [
                'error_message' => $errorMessage,
                'base_url' => $this->configuration->get("BaseURL")
            ]
        ];
    }
}
