<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Model;

final class BCAuthorizedUser extends Model
{
    protected $table = 'bc_authorized_users';
    protected $fillable = [
        'store_hash',
        'access_token',
        'user_id',
        'user_email',
        'jwt_token'
    ];
}
