<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Application\Proxy;

use Ebolution\BigcommerceAppAdapter\Application\Contracts\ConfigurationInterface;
use Ebolution\BigcommerceAppAdapter\Domain\Contracts\BCAuthorizedUserRepositoryContract;
use GuzzleHttp\Client;

class BigcommerceProxyUseCase
{
    public function __construct(
        private readonly BCAuthorizedUserRepositoryContract $repository,
        private readonly ConfigurationInterface $configuration
    ) {}

    public function __invoke(string $method, string $endpoint, $query, $body): array
    {
        if (strrpos($endpoint, 'v2') !== false) {
            // For v2 endpoints, add a .json to the end of each endpoint, to normalize against the v3 API standards
            $endpoint .= '.json';
        }

        $result = $this->makeBigCommerceAPIRequest($method, $endpoint, $query, $body);

        return [
            'result' => 'data',
            'data' => $result->getBody(),
            'status_code' => $result->getStatusCode(),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];
    }

    public function makeBigCommerceAPIRequest(string $method, string $endpoint, $query, $body): \Psr\Http\Message\ResponseInterface
    {
        $requestConfig = [
            'headers' => [
                'X-Auth-Client' => $this->configuration->get("AppClientId"),
                'X-Auth-Token'  => $this->configuration->get("AccessToken"),
                'Content-Type'  => 'application/json',
            ]
        ];

        if ($body) {
            $requestConfig['body'] = $body;
        }

        if ($query) {
            $requestConfig['query'] = $query;
        }

        $client = new Client();
        $bc_api_url = $this->configuration->get("api_base_url") . '/stores/' . $this->configuration->get("StoreHash") . '/' . $endpoint;
        $result = $client->request($method, $bc_api_url, $requestConfig);

        return $result;
    }
}
