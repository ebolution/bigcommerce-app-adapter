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
use Ebolution\BigcommerceAppAdapter\Application\Traits\JWTToken;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserSaveRequest;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserStoreHash;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserUserId;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Ebolution\BigcommerceAppAdapter\Domain\Contracts\BCAuthorizedUserRepositoryContract;

final class AuthUseCase
{
    use JWTToken;

    public function __construct(
        private readonly BCAuthorizedUserRepositoryContract $repository,
        private readonly ConfigurationInterface $configuration
    ) {}

    public function __invoke(array $data): array
    {
        // Make sure all required params have been passed
        if (!array_key_exists('code', $data) or !array_key_exists('scope', $data) or !array_key_exists('context', $data)) {
            return [
                'result' => 'error',
                'error_message' => 'Not enough information was passed to install this app.'
            ];
        }

        try {
            $client = new Client();
            $url = $this->configuration->get("RedirectURL");
            $result = $client->request('POST', $this->configuration->get("oauth_endpoint"), [
                'json' => [
                    'client_id' => $this->configuration->get("AppClientId"),
                    'client_secret' => $this->configuration->get("AppSecret"),
                    'redirect_uri' => $this->configuration->get("RedirectURL"),
                    'grant_type' => 'authorization_code',
                    'code' => $data['code'],
                    'scope' => $data['scope'],
                    'context' => $data['context'],
                ]
            ]);
        } catch (RequestException $e) {
            $errorMessage = $e->getMessage();

            // If the merchant installed the app via an external link, redirect back to the
            // BC installation failure page for this app
            if (array_key_exists('external_install', $data)) {
                return [
                    'result' => 'redirect',
                    'url' => $this->getBigcommerceLoginURL(false)
                ];
            } else {
                return [
                    'result' => 'error',
                    'error_message' => $errorMessage
                ];
            }
        }

        $statusCode = $result->getStatusCode();
        $data = json_decode($result->getBody(), true);

        $token = 'unauthorized-request';
        if ($statusCode == 200) {
            $token = $this->authorizeUser($data);

            // If the merchant installed the app via an external link, redirect back to the
            // BC installation success page for this app
            if (array_key_exists('external_install', $data)) {
                return [
                    'result' => 'redirect',
                    'url' => $this->getBigcommerceLoginURL(true)
                ];
            }
        }

        return [
            'result' => 'redirect',
            'url' => "/?token={$token}"
        ];
    }

    private function authorizeUser(array $userData): string
    {
        $context_parts = explode('/', $userData['context'], 2);
        $store_hash = $context_parts[1];
        $authUser = [
            'store_hash' => $store_hash,
            'access_token' => $userData['access_token'],
            'user_id' => (string)$userData['user']['id'],
            'user_email' => $userData['user']['email'],
        ];

        $this->repository->deleteByUserIdAndStoreHash(
            new BCAuthorizedUserUserId((string)$userData['user']['id']),
            new BCAuthorizedUserStoreHash($store_hash)
        );

        $request = new BCAuthorizedUserSaveRequest($authUser, date("Y-m-d H:i:s"));
        $auth_id = $this->repository->save($request);

        return $this->buildToken(['id' => $auth_id]);
    }

    private function getBigcommerceLoginURL(bool $succeeded = true): string
    {
        return $this->configuration->get("login_base_url") .
            '/app/' .
            $this->configuration->get("AppClientId") .
            '/install/' .
            $succeeded ? 'succeeded' : 'failed';
    }
}
