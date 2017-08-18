<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;

class ApiController extends Controller
{
    protected $guard  ='api';
    /**
     * 获取token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function token(Request $request)
    {
        $credentials=[
            'phone' => $request->phone,
            'password'  => $request->password,
//            'status' => 0,
        ];
        try {
            if (! $token = Auth::guard($this->guard)->attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }
    /**
     * @return mixed
     */
    public function refreshToken()
    {
        $token = Auth::guard($this->guard)->refresh();
        return $this->response->array(compact('token'));
    }
    //
    public function info()
    {
        return response()->json([
            'name' => 'wang_jiang',
            'age' => '18'
        ]);
    }
}
