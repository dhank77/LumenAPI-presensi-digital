<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required|min:8'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if(!$user){
            $out = [
                'status' => false,
                'message' => 'Email not found',
                'code' => 401
            ];

            return response()->json($out, $out['code']);
        }

        if(Hash::check($password, $user->password)){
            $token = Auth::attempt(['email' => $email, 'password' => $password]);
            $out = [
                'status' => true,
                'message' => 'Login successfully!',
                'code' => 200,
                'data' => $this->respondWithToken($token),
                'user' => $user
            ];
        }else{
            $out = [
                'status' => false,
                'message' => 'Wrong password!',
                'code' => 401
            ];
        }
        return response()->json($out, $out['code']);
    }

    public function register(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|unique:users|max:255',
                'nama' => 'required',
                'status' => 'required',
                'password' => 'required|min:8'
            ]
        );

        $email = $request->input('email');
        $nama = $request->input('nama');
        $password = $request->input('password');
        $status = $request->input('status');

        $hash = Hash::make($password);
        $qrcode = Str::random(40);

        $data = [
            'nama' => $nama,
            'email' => $email,
            'password' => $hash,
            'status' => $status,
            'qrcode' => $qrcode,
        ];
        if (User::create($data)) {
            $out = [
                'status' => true,
                'message' => 'User has been created!',
                'code' => 201
            ];
        } else {
            $out = [
                'status' => false,
                'message' => 'Failed Registration!',
                'code' => 404
            ];
        }

        return response()->json($out, $out['code']);
    }

}
