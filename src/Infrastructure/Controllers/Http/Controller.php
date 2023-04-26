<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\Controllers\Http;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redirect;

abstract class Controller extends BaseController
{
    public function handleResponse(array $response)
    {
        if (array_key_exists('result', $response))
        {
            if ($response['result'] === 'data') {
                return response($response['data'], $response['status_code'])->withHeaders($response['headers']);
            } elseif ($response['result'] === 'error') {
                return redirect()->route('bigcommerce-app-adapter.error', ['error_message' => $response['error_message']]);
            } elseif ($response['result'] === 'redirect') {
                return Redirect::to($response['url']);
            } elseif ($response['result'] === 'view') {
                return view("bigcommerce-app-adapter::{$response['view']}", $response['data'] ?? null);
            }
        }

        return response()->json($response);
    }
}
