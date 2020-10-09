<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class LoginUserController extends Controller
{
    //

    public function login(Request $request){
        $jwtAuth = new \JwtAuth();//Se inicializa la creacion del token

        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validateData = \Validator::make($params_array,[
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validateData->fails()){
                $signUp = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'The user could not be identified',
                    'errors' => $validate->errors()
                );
        }else{
            $hashPass = hash('sha256', $params->password);
            // dd($hashPass);
            // die();
            $signUp = $jwtAuth->signUp($params->email, $hashPass);

            if(!empty($params->getToken)){
                $signUp = $jwtAuth->signUp($params->email, $hashPass, true);

            }
        }

        return response()->json($signUp, 200);


    }
}
