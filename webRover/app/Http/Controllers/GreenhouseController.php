<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\GreenHouse;

class GreenhouseController extends Controller
{   
    public function __construct(){
        $this->middleware('api.auth', ['except' => ['getIdentity']]); 
    }

    private function getIdentity($request){
        $jwtAuth = new \JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true); 

        return $user;
        // $user = $this->getIdentity($request); 
    }

    public function getGreenHousesByUser($id){
        $greenHouse = GreenHouse::where('id_user', $id)->get();

        foreach($greenHouse as $greenHouses){
            $greenHouseList = $greenHouses;
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'greenHouses' => $greenHouse
                            // $greenHouseList
        ],200);
    }


    public function store(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params_array) && !empty($params)){
            $user = $this->getIdentity($request); 

            $validateData = \Validator::make($params_array,[
                'name' => ['required'],
                'department' => ['required', 'alpha'],
                'city' => ['required', 'alpha'],
                'address' => ['required'],
                'weatherType' => ['required', 'alpha']
            ]);

            if($validateData->fails()){
                $dataMessage = [
                    'code' => 400,
                    'status' => 'Error',
                    'message' => 'There was an error validating the data'
                ];
            }else{
                $greenHouse = new GreenHouse();
                
                $greenHouse->id_user = $user->sub;
                $greenHouse->name = $params->name;
                $greenHouse->department = $params->department;
                $greenHouse->city = $params->city;
                $greenHouse->address = $params->address;
                $greenHouse->weatherType = $params->weatherType;

                $greenHouse->save();

                $dataMessage = [
                    'code' => 200,
                    'status' => 'success',
                    'greenHouse' => $greenHouse
                ];
                
            }
        }else{
            $dataMessage = [
                'code' => 400,
                'status' => 'Error',
                'message' => 'Make sure to send the data correctly'
            ];
        }

        return response()->json($dataMessage, $dataMessage['code']);
    }

    public function update($id, Request $request){
        $json = $request->input('json', null);
        $params_array = json_decode($json);

        if(!empty($params_array)){
            $validateData = \Validator::make($params_array,[
                'name' => ['required'],
                'department' => ['required', 'alpha'],
                'city' => ['required', 'alpha'],
                'address' => ['required'],
                'weatherType' => ['required', 'alpha']
            ]);

            if($validateData->fails()){
                $dataMessage = [
                    'code' => 400,
                    'status' => 'Error',
                    'message' => 'There was an error validating de data'
                ];
            }else{
                unset($params_array['id']);
                unset($params_array['id_user']);
                unset($params_array['created_at']);
                unset($params_array['user']);

                $user = $this->getIdentity($request); 

                $greenHouse = GreenHouse::where('id', $id)
                                        ->where('id_user', $user->sub)
                                        ->first();

                if(!empty($greenHouse) && is_object($greenHouse)){
                    $greenHouse->update($params_array);

                    $dataMessage = [
                        'code' => 200,
                        'status' => 'Success',
                        'greenHouses' => $greenHouse,
                        'changes'=>$params_array
                    ];
                }else{
                    $dataMessage = [
                        'code' => 400,
                        'status' => 'Error',
                        'message'=> 'You are not the owner of the greenhouse that you are trying to update'
                    ];
                }
            }
        }else{
            $dataMessage = [
                'code' => 400,
                'status' => 'Error',
                'message' => 'Incorrect Data'
            ];
        }
        return response()->json($dataMessage, $dataMessage['code']);
    }

    public function destroy($id, Request $request){
        $user = $this->getIdentity($request); 
        
        $greenHouse = GreenHouse::where('id', $id)
                                ->where('id_user', $user->sub)
                                ->first();
        
        if(!empty($greenHouse)){
            $greenHouse->delete();

            $dataMessage = [
                'code' => 200,
                'status' => 'success',
                'greenHouses' => $greenHouse
            ];
        }else{
            $data=[
                'code' => 400,
                'status' => 'Error',
                'message' => 'The greenhouse to be deleted does not exists'
            ];
        }
        return response()->json($dataMessage, $dataMessage['code']);
    }
}
