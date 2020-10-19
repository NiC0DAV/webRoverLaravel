<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function userRegister(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params_array) && !empty($params)){
            $validateData = \Validator::make($params_array,[
                'name' => ['required','alpha'],
                'surname' => ['required', 'alpha'],
                'identification' => ['required', 'alpha_num', 'unique:users'],
                'contactNumber' => ['required', 'alpha_num', 'unique:users'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required']
            ]);
        
            if($validateData->fails()){
                $dataMessage = array(
                    'code' => 400,
                    'status' => 'Error',
                    'message' => 'The user was not created successfully',
                    'errors' => $validateData->errors()
                );
            }else{
                
                $hashPassword = hash('sha256', $params->password);

                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->identification = $params_array['identification'];
                $user->contactNumber = $params_array['contactNumber'];
                $user->email = $params_array['email'];
                $user->password = $hashPassword;
                $user->role = 'ROLE_OWNER';

                $user->save();
                
                $dataMessage = array(
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'The user was created successfully',
                    'user' => $user
                );
                
            }    

        }else{
            $dataMessage = array(
                'code' => 400,
                'status' => 'Error',
                'message' => 'There was a problem with the entered data'
            );
         }

        $params_array = array_map('trim', $params_array);

        return response()->json($dataMessage, $dataMessage['code']);

    }

    public function userLogin(Request $request){
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

    public function userUpdate(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        if ($checkToken && !empty($params_array)) {
            $user = $jwtAuth->checkToken($token, true);

            $validateData = \Validator::make($params_array, [
                'email' => ['required', 'email', 'unique:users'.$user->sub],
                'contactNumber' => ['required', 'alpha_num', 'unique:users'.$user->sub]
            ]);

            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['name']);
            unset($params_array['surname']);
            unset($params_array['identification']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            $user_update = User::where('id', $user->sub)->update($params_array);
            
            $data = array(
                'code' => 200,
                'status' => 'Success',
                'user' => $user,
                'changes' => $params_array
            );
        }else{
            $data = array(
                'code' => 400,
                'status' => 'Error',
                'message' => 'Error el usuario no esta identificado'
            );
        }

        return response()->json($data, $data['code']);
    }

}
