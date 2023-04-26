<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\ServiceProviders;

use Ebolution\BigcommerceAppAdapter\Application\Contracts\ConfigurationInterface;
use Ebolution\BigcommerceAppAdapter\Application\Contracts\PersistenceInterface;
use Ebolution\BigcommerceAppAdapter\Domain\Contracts\BCAuthorizedUserRepositoryContract;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Helpers\LaravelConfigurationHelper;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Helpers\LaravelSessionHelper;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Repositories\EloquentBCAuthorizedUserRepository;
use Illuminate\Support\ServiceProvider;

final class DependencyServicesProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ConfigurationInterface::class, LaravelConfigurationHelper::class);
        $this->app->bind(PersistenceInterface::class, LaravelSessionHelper::class);
        $this->app->bind(BCAuthorizedUserRepositoryContract::class, EloquentBCAuthorizedUserRepository::class);
    }
}
