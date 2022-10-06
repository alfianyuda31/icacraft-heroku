<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //library untuk validasi password dari fortify
    use PasswordValidationRules;

    //fungsi untuk login
    public function login(Request $request)
    {
        try{
            //validasi input
            $request->validate([
                'email' => 'email|required', //validasi apakah ada inputan dan format email
                'password' => 'required'
            ]);

            //Mengecek credentials (login)
            $credentials = request(['email','password']);
            if(!Auth::attempt($credentials)){
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed, 500');
            }

            //jika hash tidak sesuai maka beri error
            $user = User::where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password, [])){
                throw new \Exception('Invalid Credentials');
            }

            //jika berhasil login
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');

        } catch(Exception $error){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed, 500');
        }
    }

    //fungsi untuk register
    public function register(Request $request)
    {
        try {
            //validasi inputan
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules()
            ]);

            //jika sudah tervalidasi dan benar maka membuat user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'houseNumber' => $request->houseNumber,
                'phoneNumber' => $request->phoneNumber,
                'postalCode' => $request->postalCode,
                'password' => Hash::make($request->password),
            ]);

            //untuk memanggil data user yang tadi telah dibuat
            $user = User::where('email', $request->email)->first();

            //mengambil token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            //mengembalikan data user dan token
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ],'User Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    //fungsi untuk logout
    public function logout(Request $request)
    {
        //mendapatkan token yang diperoleh dari login
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }

    //fungsi api untuk mengambil data user dikirim di mobile
    public function fetch(Request $request)
    {
        return ResponseFormatter::success(
            $request->user(),'Data Profile User Berhasil Diambil');
    }

    //fungsi untuk update profile
    public function updateProfile(Request $request)
    {
        //memanggil semua data user dari database
        $data = $request->all();


        $user = Auth::user();
        $user->update($data);

        return ResponseFormatter::success($user, 'Profile Updated');

    }

    //fungsi untuk upload foto
    public function updatePhoto(Request $request)
    {
        //validasi format file
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048'
        ]);

        //respon apabila error
        if($validator->fails())
        {
            return ResponseFormatter::error(
                ['error' => $validator->errors()], 
                'Update photo fails', 401
            );
        }

        //apabila validasi berhasil
        if($request->file('file'))
        {
            //simpan file foto di storage
            $file = $request->file->store('assets/user','public');

            //simpan foto ke database (urlnya saja)
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();

            return ResponseFormatter::success([$file], 'File successfully uploaded');
        }

    }
}
