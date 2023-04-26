<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Domain\ValueObjects;

final class BCAuthorizedUserUserEmail
{
    public function __construct(
        private string $value
    ) {}

    public function value(): string
    {
        return $this->value;
    }
}
