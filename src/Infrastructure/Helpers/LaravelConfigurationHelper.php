<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\Helpers;

use Ebolution\BigcommerceAppAdapter\Application\Contracts\ConfigurationInterface;
use Ebolution\BigcommerceAppAdapter\Application\Contracts\PersistenceInterface;

class LaravelConfigurationHelper implements ConfigurationInterface
{
    public function get(string $item): string
    {
        $method = "get${item}";
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return config("bigcommerce-app-adapter.{$item}", "");
    }

    private function getAppClientId()
    {
        if (env('APP_ENV') === 'local') {
            return env('BC_LOCAL_CLIENT_ID');
        } else {
            return env('BC_APP_CLIENT_ID');
        }
    }

    private function getAppSecret()
    {
        if (env('APP_ENV') === 'local') {
            return env('BC_LOCAL_SECRET');
        } else {
            return env('BC_APP_SECRET');
        }
    }

    private function getAccessToken()
    {
        if (env('APP_ENV') === 'local') {
            return env('BC_LOCAL_ACCESS_TOKEN');
        } else {
            return $this->fromSession('access_token');
        }
    }

    private function getStoreHash()
    {
        if (env('APP_ENV') === 'local') {
            return env('BC_LOCAL_STORE_HASH');
        } else {
            return $this->fromSession('store_hash');
        }
    }

    private function getBaseURL()
    {
        return env('APP_URL');
    }

    private function getRedirectURL()
    {
        return route('bigcommerce-app-adapter.auth');
    }

    private function fromSession(string $item): string
    {
        $session = app()->make(PersistenceInterface::class);
        $session_data = $session->retrieve();

        return $session_data[$item] ?? "";
    }
}
