<?php


namespace App\Http\Controllers\API;


use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    /**
     * Login api
     *
     * @return JsonResponse
     */

    public function login(Request $request)

    {
        ///if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        if (User::query()->firstWhere(['api_key'=>$request->api_key,'api_secret'=>$request->api_secret]) !=null){
           // $user = Auth::user();

            $user=User::query()->firstWhere(['api_key'=>$request->api_key,'api_secret'=>$request->api_secret]);
            //$user->tokens()->delete();
            $success['token'] = $user->createToken('ApiToken')->accessToken;;
            $success['name'] = $user->name;
            return $this->sendResponse($success, 'User login successfully.');

        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);

        }

    }

}
