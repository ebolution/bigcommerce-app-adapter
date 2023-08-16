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
use Ebolution\BigcommerceAppAdapter\Application\Traits\JWTToken;
use Ebolution\BigcommerceAppAdapter\Domain\Contracts\BCAuthorizedUserRepositoryContract;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserSaveRequest;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserStoreHash;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserUserId;

final class LoadUseCase extends WithBigCommerceSignedRequest
{
    use JWTToken;

    public function __construct(
        private readonly BCAuthorizedUserRepositoryContract $repository,
        private readonly ConfigurationInterface $configuration
    ) {
        parent::__construct($this->configuration);
    }

    protected function handle(array $verifiedSignedRequestData): array
    {
        // "sub": "stores/<store-hash>"
        $store_hash = substr($verifiedSignedRequestData['sub'], 7);

        // Get store's owner authorization
        $authUser = $this->repository->findByUserIdAndStoreHash(
            new BCAuthorizedUserUserId($verifiedSignedRequestData['owner']['id']),
            new BCAuthorizedUserStoreHash($store_hash)
        );
        if (!$authUser) {
            return [
                'result' => 'error',
                'error_message' => "User #{$verifiedSignedRequestData['user']['id']} ({$verifiedSignedRequestData['user']['email']}) has not been authorized to use this App at {$verifiedSignedRequestData['context']}"
            ];
        }

        // If this is not the store owner, authorize user
        if ($verifiedSignedRequestData['owner']['id'] !== $verifiedSignedRequestData['user']['id']) {
            $this->authorizeNewUser($verifiedSignedRequestData, $authUser['access_token']);
        }

        $token = $this->buildToken(['id' => $authUser['id']]);

        return [
            'result' => 'redirect',
            'url' => "{$verifiedSignedRequestData['url']}?token={$token}"
        ];
    }

    private function authorizeNewUser(array $verifiedSignedRequestData, string $access_token): void
    {
        $user_id = new BCAuthorizedUserUserId($verifiedSignedRequestData['user']['id']);
        $store_hash = new BCAuthorizedUserStoreHash($verifiedSignedRequestData['store_hash']);
        if (!$this->repository->findByUserIdAndStoreHash($user_id, $store_hash)) {
            $authUser = [
                'store_hash' => $verifiedSignedRequestData['store_hash'],
                'access_token' => $access_token,
                'user_id' => (string)$verifiedSignedRequestData['user']['id'],
                'user_email' => $verifiedSignedRequestData['user']['email']
            ];

            $request = new BCAuthorizedUserSaveRequest($authUser, date("Y-m-d H:i:s"));
            $this->repository->save($request);
        } else {
            $fieldsToUpdate = [
                'access_token' => $access_token,
            ];
            $request = new BCAuthorizedUserSaveRequest($fieldsToUpdate, date("Y-m-d H:i:s"));
            $this->repository->updateByUserIdAndStoreHash($user_id, $store_hash, $request);
        }
    }
}
