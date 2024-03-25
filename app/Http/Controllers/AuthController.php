<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // make validation
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|confirmed'
        ], [
            'username.required' => 'Nama Harus Diisi',
            'email.required' => 'Email Harus Diisi',
            'email.email' => 'Format email tidak sesuai',
            'email.unique' => 'Email sudah ada',
            'password.required' => 'Password Harus Diisi',
            'password_confirmation.required' => 'Konfirmasi password harus diisi',
            'password_confirmation.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        if ($validator->fails()) response()->json([
            'status' => false,
            'message' => $validator->errors()
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        // make data users
        $user = User::create($data);

        // make code otp
        $otp = $user->otp()->create(['code' => random_int(100000, 999999)]);

        $phone = $user->phone;
        $message = "Kode OTP Anda \n$otp->code";

        $this->waOtp($phone, $message);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menyimpan data',
            'data' => $user
        ]);
    }
}
