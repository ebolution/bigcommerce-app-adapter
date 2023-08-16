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
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserStoreHash;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserUserId;

final class RemoveUserUseCase extends WithBigCommerceSignedRequest
{
    public function __construct(
        private readonly BCAuthorizedUserRepositoryContract $repository,
        private readonly ConfigurationInterface $configuration
    ) {
        parent::__construct($this->configuration);
    }

    public function handle(array $verifiedSignedRequestData): array
    {
        $this->repository->deleteByUserIdAndStoreHash(
            new BCAuthorizedUserUserId($verifiedSignedRequestData['user']['id']),
            new BCAuthorizedUserStoreHash($verifiedSignedRequestData['store_hash'])
        );

        return [];
    }
}
