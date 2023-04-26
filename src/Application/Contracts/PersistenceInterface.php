<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Application\Contracts;

interface PersistenceInterface
{
    public function persist(array $data): void;
    public function retrieve(): array;
}
