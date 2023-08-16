<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\Middleware;

use Closure;
use Ebolution\BigcommerceAppAdapter\Application\App\ValidateTokenUseCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateJWTToken
{
    public function __construct(
        private readonly ValidateTokenUseCase $useCase
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Bypass token validation for local development
        if (env('APP_ENV') !== 'local') {
            $jwt_token = $request->header('X-Auth-Token');
            try {
                $this->useCase->__invoke($jwt_token);
            } catch (\Exception $ex) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => $ex->getMessage(),
                ], 401);
            }
        }

        return $next($request);
    }
}
