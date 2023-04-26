<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\Repositories;

use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserSaveRequest;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserStoreHash;
use Ebolution\BigcommerceAppAdapter\Domain\ValueObjects\BCAuthorizedUserUserId;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Repositories\BCAuthorizedUser as Model;
use Ebolution\BigcommerceAppAdapter\Domain\Contracts\BCAuthorizedUserRepositoryContract;

final class EloquentBCAuthorizedUserRepository implements BCAuthorizedUserRepositoryContract
{
    private Model $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function findByUserIdAndStoreHash(BCAuthorizedUserUserId $user_id, BCAuthorizedUserStoreHash $store_hash): ?array
    {
        $response = $this->model
            ->where('user_id', $user_id->value())
            ->where('store_hash', $store_hash->value())
            ->first();

        return $response ? $response->toArray() : null;
    }

    public function deleteByUserIdAndStoreHash(BCAuthorizedUserUserId $user_id, BCAuthorizedUserStoreHash $store_hash): bool
    {
        return $this->model
            ->where('user_id', $user_id->value())
            ->where('store_hash', $store_hash->value())
            ->delete();
    }

    public function deleteByStoreHash(BCAuthorizedUserStoreHash $store_hash): bool
    {
        return $this->model
            ->where('store_hash', $store_hash->value())
            ->delete();
    }

    public function save(BCAuthorizedUserSaveRequest $request): ?int
    {
        $response = $this->model->create($request->handler());

        return ($response) ? $response->id : null;
    }

    public function updateByUserIdAndStoreHash(BCAuthorizedUserUserId $user_id, BCAuthorizedUserStoreHash $store_hash, BCAuthorizedUserSaveRequest $request): ?int
    {
        $record = $this->model
            ->where('user_id', $user_id->value())
            ->where('store_hash', $store_hash->value())
            ->first();

        if ($record) {
            $record->update($request->handler());
        }

        return ($record) ? $record->id : null;
    }
}
