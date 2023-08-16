<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Domain\Contracts;

use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserUserId;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserStoreHash;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserSaveRequest;

interface BCAuthorizedUserRepositoryContract
{
    public function findById(int $id): ?array;
    public function findByUserIdAndStoreHash(BCAuthorizedUserUserId $user_id, BCAuthorizedUserStoreHash $store_hash): ?array;
    public function deleteByUserIdAndStoreHash(BCAuthorizedUserUserId $user_id, BCAuthorizedUserStoreHash $store_hash): bool;
    public function deleteByStoreHash(BCAuthorizedUserStoreHash $store_hash): bool;
    public function save(BCAuthorizedUserSaveRequest $request): ?int;
    public function updateByUserIdAndStoreHash(BCAuthorizedUserUserId $user_id, BCAuthorizedUserStoreHash $store_hash, BCAuthorizedUserSaveRequest $request): ?int;
}
