<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Application\Contracts;

interface ConfigurationInterface
{
    public function get(string $item): string;
}
