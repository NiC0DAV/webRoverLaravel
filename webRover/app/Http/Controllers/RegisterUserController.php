<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class RegisterUserController extends Controller
{
    //
    public function register(Request $request){
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
}
