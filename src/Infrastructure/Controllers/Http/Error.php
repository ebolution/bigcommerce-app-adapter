<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\Controllers\Http;

use Ebolution\BigcommerceAppAdapter\Application\App\ErrorUseCase;
use Illuminate\Http\Request;

class Error extends Controller
{
    public function __construct(
        private readonly ErrorUseCase $useCase
    ) {}

    public function __invoke(Request $request)
    {
        return $this->handleResponse(
            $this->useCase->__invoke($request->all())
        );
    }
}