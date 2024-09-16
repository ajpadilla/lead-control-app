<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UnauthorizedUserResource;

class CheckRolePermission
{

    /**
     * @param Request $request
     * @param Closure $next
     * @param $permission
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, $permission): JsonResponse
    {
        $user = auth()->user();

        if (!$user->hasPermission($permission)) {
            return response()->json(new UnauthorizedUserResource([
                'Unauthorized',
            ]), 403);
        }

        return $next($request);
    }
}
