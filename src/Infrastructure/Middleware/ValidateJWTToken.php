<?php

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
        $jwt_token = $request->header('X-Auth-Token');
        try {
            $this->useCase->__invoke($jwt_token);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
