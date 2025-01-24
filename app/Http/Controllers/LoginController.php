<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LoginResource;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password'); # to check login credentials 

            if (!Auth::attempt($credentials)) { # If login doesn't match with DB, return error msg
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials!',
                ], 401);
            }
            # Retrieve authenticated user
            $user = Auth::guard('ctj-api')->user();  
                       
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'data' => [
                    'user' => new LoginResource($user)
                ],
            ], 200);
        }catch (AuthenticationException $e) {
            return response()->json([
                'status'  => Response::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => Response::HTTP_NOT_FOUND,
                'message' => 'Model not found!',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) { 
            Log::channel('user_login_error')->info($e);
            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error!',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }  
    }
}
