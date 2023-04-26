<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\ServiceProviders;

use Ebolution\ModuleManager\Infrastructure\ServiceProviders\RouteServicesProvider as ModuleManagerRouteServicesProvider;

class RouteServicesProvider extends ModuleManagerRouteServicesProvider
{
    const BASE_PATH = __DIR__;

    protected bool $loadApi = false;
    protected bool $loadWeb = true;
}
