<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminController extends BaseController
{
    /**
     * @throws ValidationException
     */
    /**
     * @throws ValidationException
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator =  Validator::make($request->all(),[
            'email'=>'required|max:100|exists:admins,email',
            'password'=>['required','string','min:6'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        if(! $token = auth('admin-api')->attempt($validator->validated())){
            return response()->json(['error' =>  'unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth('admin-api')->logout();
        return response()->json(['message' =>'successfully logged out!']);
    }

    public function createNewToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => strtotime(date('Y-m-d H:i:s', strtotime("+60 min"))),
            'Admin' => auth('admin-api')->user()
        ]);
    }}
