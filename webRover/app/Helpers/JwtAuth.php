<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth{

    public $key;

    public function __construct(){
        $this->key = 'ROVERAGRICULTORUSBBOG';
    }

    public function signUp($email, $password, $getToken = null){
        //Buscar si existen los datos de logeo //Una vez creada la primera clase crear un service provider para que incruste esta clase en el framework y de est manera pueda ser usada
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first();

        //Comprobar si son correctos
        $signUp = false;

        if(is_object($user)){
            $signUp = true;
        }
        
        //Generar el token con los datos del usuario
        if($signUp){
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'identification' => $user->identification,
                'iat' => time(), //Fecha de creacion del token
                'exp' => time()+(7*24*60*60) //Fecha de expiracion
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');

            $decodeJwt = JWT::decode($jwt, $this->key, ['HS256']);

            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data = $decodeJwt;
            }
        }else{
            $data = array(
                'status' => 'Error',
                'message' => 'Incorrect login attempt'
            );
        }

        return $data;
    }

    public function checkToken($jwt, $getIdentity = false){

        $auth = false;

        try{
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        }catch (\UnexpectedValueException $e){
            $auth = false;
        }catch (\DomainException $e){
            $auth = false;
        }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false;
        }

        if($getIdentity){
            return $decoded;
        }

        return $auth;

    }
}