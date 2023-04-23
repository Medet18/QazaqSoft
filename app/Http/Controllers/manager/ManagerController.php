<?php

namespace App\Http\Controllers\manager;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ManagerController extends BaseController
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
            'email'=>'required|max:100|exists:managers,email',
            'password'=>['required','string','min:6'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        if(! $token = auth('manager-api')->attempt($validator->validated())){
            return response()->json(['error' =>  'unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth('manager-api')->logout();
        return response()->json(['message' =>'successfully logged out!']);
    }

    public function createNewToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => strtotime(date('Y-m-d H:i:s', strtotime("+60 min"))),
            'Admin' => auth('manager-api')->user()
        ]);
    }}
