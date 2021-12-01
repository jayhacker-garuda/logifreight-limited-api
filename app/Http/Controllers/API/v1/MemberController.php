<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function mail_box($umi)
    {
        $mail_address = "3224 McDonald Avenue, Orlando, FL, Box: #".$umi;
        return $mail_address;
    }
    public function registration(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'full_name' => 'required|string|min:6',
            'email' => 'required|email|unique:members,email',
            'trn' => 'required|min:6|max:6|unique:members,trn',
            'address' => 'required|string',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password|min:8'
        ]);

        if($validation->fails())
        {
            return response()->json(['errors' => $validation->errors()], 202);
        }

        $memberData = $request->all();

        // dd($memberData['password']);
        $member = Member::create([
            'umi' => random_int(1000, 9999),
            'full_name' => $memberData['full_name'],
            'email' => $memberData['email'],
            'trn' => $memberData['trn'],
            'address' => $memberData['address'],
            'password' => bcrypt($memberData['password'])
        ]);


        $resArr = [];
        $resArr['message'] = 'Account Created Successfully';

        $member->mail_box = $this->mail_box($member->umi);
        $member->update();

        return response()->json($resArr,200);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'email' => 'required|string',
            'password' => 'required|min:8',
        ]);

        if($validation->fails())
        {
            return response()->json($validation->errors(), 202);
        }

        $credentials = $request->only('email','password');
        // dd(Auth::guard('member'));
        if (Auth::guard('member')->attempt($credentials)){

            $member = Auth::guard('member')->user();

            $resArr = [];
            $resArr['token'] = $member->createToken('api-application')->accessToken;
            $resArr['umi'] = $member->umi;
            $resArr['member_id'] = $member->id;
            $resArr['member_name'] = $member->full_name;

        return response()->json($resArr,200);

        }else{
            return response()->json(['error' => 'Unauthorized Access'], 203);
        }
    }

    public function logout(Request $request) {

        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];

        return response()->json($response, 200);

    }
}