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
use Ebolution\BigcommerceAppAdapter\Application\Contracts\PersistenceInterface;
use Ebolution\BigcommerceAppAdapter\Domain\Contracts\BCAuthorizedUserRepositoryContract;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserSaveRequest;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserStoreHash;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserUserId;

final class LoadUseCase extends WithBigCommerceSignedRequest
{
    public function __construct(
        private readonly BCAuthorizedUserRepositoryContract $repository,
        private readonly ConfigurationInterface $configuration,
        private readonly PersistenceInterface $session
    ) {
        parent::__construct($this->configuration);
    }

    protected function handle(array $verifiedSignedRequestData): array
    {
        // Get store's owner authorization
        $authUser = $this->repository->findByUserIdAndStoreHash(
            new BCAuthorizedUserUserId($verifiedSignedRequestData['owner']['id']),
            new BCAuthorizedUserStoreHash($verifiedSignedRequestData['store_hash'])
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

        $this->session->persist($authUser);

        return [
            'result' => 'redirect',
            'url' => '/'
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
