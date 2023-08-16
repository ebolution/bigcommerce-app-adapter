<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\Routes;

use Ebolution\BigcommerceAppAdapter\Infrastructure\Controllers\Http\Auth;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Controllers\Http\Load;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Controllers\Http\Uninstall;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Controllers\Http\RemoveUser;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Controllers\Http\Error;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Controllers\Http\BigcommerceProxy;
use Ebolution\BigcommerceAppAdapter\Infrastructure\Middleware\ValidateJWTToken;
use Illuminate\Support\Facades\Route;

Route::get('auth', Auth::class)->name('bigcommerce-app-adapter.auth');
Route::get('load', Load::class);
Route::get('uninstall', Uninstall::class);
Route::get('remove-user', RemoveUser::class);
Route::get('error', Error::class)->name('bigcommerce-app-adapter.error');
Route::group(['prefix' => 'bc-api'], function () {
    Route::any('{endpoint}', BigcommerceProxy::class)
        ->where('endpoint', 'v2\/.*|v3\/.*')
        ->middleware(ValidateJWTToken::class);
});
