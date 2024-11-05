<?php

namespace App\Http\Middleware;

use Closure;
use Kreait\Firebase\Auth;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Http\Request;
use App\Models\UserAccount;

class FirebaseAuthMiddleware
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');
        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return response()->json(['error' => 'Authorization token not provided or invalid format'], 401);
        }

        $idToken = explode(' ', $authorizationHeader)[1] ?? null;

        if (!$idToken) {
            return response()->json(['error' => 'Token not found'], 401);
        }

        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            $firebaseUserId = $verifiedIdToken->claims()->get('sub');

            $userAccount = UserAccount::where('firebase_id', $firebaseUserId)->first();

            if (!$userAccount) {
                return response()->json(['error' => 'User not found in the system'], 404);
            }

            // Attach the user model to the request for access in controllers
            $request->attributes->set('authenticated_user', $userAccount);
            return $next($request);

        } catch (InvalidToken $e) {
            return response()->json(['error' => 'Invalid token: ' . $e->getMessage()], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token verification failed: ' . $e->getMessage()], 500);
        }
    }
}
