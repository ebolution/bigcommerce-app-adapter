<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Application\App;


use Ebolution\BigcommerceAppAdapter\Application\Contracts\ConfigurationInterface;
use Ebolution\BigcommerceAppAdapter\Domain\Contracts\BCAuthorizedUserRepositoryContract;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserStoreHash;

final class UninstallUseCase extends WithBigCommerceSignedRequest
{
    public function __construct(
        private readonly BCAuthorizedUserRepositoryContract $repository,
        private readonly ConfigurationInterface $configuration
    ) {
        parent::__construct($this->configuration);
    }

    protected function handle(array $verifiedSignedRequestData): array
    {
        $this->repository->deleteByStoreHash(
            new BCAuthorizedUserStoreHash($verifiedSignedRequestData['store_hash'])
        );

        return [];
    }
}
