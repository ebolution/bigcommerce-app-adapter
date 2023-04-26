<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Domain;

use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserStoreHash;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAUthorizedUserAccessToken;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAUthorizedUserUserId;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAUthorizedUserUserEmail;

final class BCAuthorizedUser
{
    public function __construct(
        private readonly BCAuthorizedUserStoreHash $store_hash,
        private readonly BCAUthorizedUserAccessToken $access_token,
        private readonly BCAUthorizedUserUserId $user_id,
        private readonly BCAUthorizedUserUserEmail $user_email,
    ) {}

    public function storeHash(): BCAuthorizedUserStoreHash
    {
        return $this->store_hash;
    }

    public function acessToken(): BCAUthorizedUserAccessToken
    {
        return $this->access_token;
    }

    public function userId(): BCAUthorizedUserUserId
    {
        return $this->user_id;
    }

    public function userEmail(): BCAUthorizedUserUserEmail
    {
        return $this->user_email;
    }
}
