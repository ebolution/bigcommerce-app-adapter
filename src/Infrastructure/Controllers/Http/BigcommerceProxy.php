<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\Controllers\Http;

use Ebolution\BigcommerceAppAdapter\Application\Proxy\BigcommerceProxyUseCase;
use Illuminate\Http\Request;

class BigcommerceProxy extends Controller
{
    public function __construct(
        private readonly BigcommerceProxyUseCase $useCase
    ) {}

    public function __invoke(Request $request, string $endpoint)
    {
        return $this->handleResponse(
            $this->useCase->__invoke($request->method(), $endpoint, $request->getContent(), $request->query())
        );
    }
}
